<?php

namespace app\Models;

use core\App;
use core\Models\ValidationModel;

class ProfileModel extends ValidationModel
{
    const UPDATE_PROFILE = 1;
    const UPDATE_PASSWORD = 2;

    public string $idMurid = '';
    public string $noTel = '';
    public string $namaM = '';
    public string $kLMurid = '';
    public string $kLMuridNew = '';
    public string $kLMuridNewConf = '';
    public int $type = 1; // TODO: Change to csrf token

    public function __construct($data)
    {
        if (empty($data)) {
            $data = (array) App::$app->user;
        }

        parent::loadData($data);
    }

    public function rules(): array
    {
        return [
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 20]],
            'noTel' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 10], [self::RULE_MAX, 'max' => 12]],
            'namaM' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 100]],
        ];
    }

    public function fieldNames(): array
    {
        return [
            'idMurid' => 'ID Murid',
            'noTel' => 'No Telefon',
            'namaM' => 'Nama',
            'kLMurid' => 'Kata Laluan Sekarang',
            'kLMuridNew' => 'Kata Laluan Baharu',
            'kLMuridNewConf' => 'Kata Laluan Baharu Sekali Lagi',
        ];
    }

    public function rulesUpdatePassword(): array
    {
        return [
            'kLMurid' => [self::RULE_REQUIRED],
            'kLMuridNew' => [self::RULE_REQUIRED],
            'kLMuridNewConf' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'kLMuridNew', 'matchMsg' => 'must match with new password']],
        ];
    }

    public function verifyNoDuplicate(array $old_data = []): bool
    {
        $oldIdMurid = $this->getOldData($old_data, 'idMurid', App::$app->user);
        if ($oldIdMurid == $this->idMurid) {
            return true;
        }
        $check = RegisterModel::checkDatabaseForDuplicates($this->idMurid);
        if (!$check) {
            $this->addError(false, 'idMurid', self::RULE_UNIQUE);
        }
        return $check;
    }

    public function updateDatabase(array $old_data = []): bool
    {
        $oldIdMurid = $this->getOldData($old_data, 'idMurid', App::$app->user);
        $oldNoTel = $this->getOldData($old_data, 'noTel', App::$app->user);

        $update = App::$app->database->update('murid', ['idMurid', 'noTel'], $this, ['idMurid' => $oldIdMurid]);
        $updateNama = App::$app->database->update('telefon', ['noTel', 'namaM'], $this, ['noTel' => $oldNoTel], true);

        return $update && $updateNama;
    }

    public function updateDatabasePasswordOnly(): bool
    {
        $this->kLMuridNew = password_hash($this->kLMuridNew, PASSWORD_BCRYPT);
        return App::$app->database->update('murid', ['kLMurid'], ['kLMurid' => $this->kLMuridNew], ['idMurid' => App::$app->user->idMurid]);
    }

    public function checkPassword(): bool
    {
        $check = password_verify($this->kLMurid, App::$app->user->kLMurid);
        if (!$check) {
            $this->addError(false, 'kLMurid', self::RULE_MATCH, ['match', 'is incorrect']);
        }
        return $check;
    }

    private function getOldData(array $old_data, string $toFind, object|string $fallback)
    {
        return $old_data[$toFind] ?? (is_object($fallback) ? $fallback->{$toFind} : $fallback);
    }
}