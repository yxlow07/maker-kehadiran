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
            'idMurid' => [self::RULE_REQUIRED],
            'noTel' => [self::RULE_REQUIRED],
            'namaM' => [self::RULE_REQUIRED],
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

    public function verifyNoDuplicate(): bool
    {
        if ($this->idMurid == App::$app->session->get('user')->idMurid) {
            return true;
        } else {
            $check = RegisterModel::checkDatabaseForDuplicates($this->idMurid);
            if (!$check) {
                $this->addError(false, 'idMurid', self::RULE_UNIQUE);
            }
            return $check;
        }
    }

    public function updateDatabase(): bool
    {
        $update = App::$app->database->update('murid', ['idMurid', 'noTel'], $this, ['idMurid' => App::$app->user->idMurid]);
        $updateNama = App::$app->database->update('telefon', ['noTel', 'namaM'], $this, ['noTel' => App::$app->user->noTel], true);

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
}