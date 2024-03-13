<?php

namespace app\Models;

use core\App;
use core\Models\ValidationModel;

class RegisterModel extends ValidationModel
{
    public string $idMurid = '';
    public string $kLMurid = '';
    public string $noTel = '';
    public string $confirmkLMurid = '';
    public string $namaM = '';

    public function __construct(array $data)
    {
        parent::loadData($data);
    }

    public function rules(): array
    {
        return [
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 12]],
            'noTel' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 10], [self::RULE_MAX, 'max' => 12]],
            'kLMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'confirmkLMurid' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'kLMurid', 'matchMsg' => 'must match with kLMurid']],
        ];
    }

    public function fieldNames(): array
    {
        return [
            'idMurid' => 'ID Murid',
            'kLMurid' => 'Password',
            'confirmkLMurid' => 'Confirm password',
            'noTel' => 'Nombor Telefon',
            'namaM' => 'Nama Murid',
        ];
    }

    public function registerUser(): bool
    {
        $this->kLMurid = password_hash($this->kLMurid, PASSWORD_BCRYPT);

        return App::$app->database->insert('murid', ['idMurid', 'noTel', 'kLMurid'], $this);
    }

    public function registerName(): bool
    {
        return App::$app->database->insert('telefon', ['noTel', 'namaM'], $this);
    }

    public function verifyNoDuplicate(): bool
    {
        $check = self::checkDatabaseForDuplicates($this->idMurid);
        if (!$check) {
            $this->addError(false, 'idMurid', self::RULE_UNIQUE);
        }
        return $check;
    }

    /**
     * @param string $idMurid
     * @return bool If user exists, then return false
     */
    public static function checkDatabaseForDuplicates(string $idMurid): bool
    {
        $user = App::$app->database->findOne('murid', ['idMurid' => $idMurid], class: UserModel::class);
        if ($user instanceof UserModel) {
            return false;
        } else {
            return true;
        }
    }
}