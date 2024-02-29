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

    public function __construct(array $data)
    {
        parent::loadData($data);
    }

    public function rules(): array
    {
        return [
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'noTel' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 10], [self::RULE_MAX, 'max' => 11]],
            'kLMurid' => [self::RULE_REQUIRED],
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
        ];
    }

    public function registerUser(): bool
    {
        $this->kLMurid = password_hash($this->kLMurid, PASSWORD_BCRYPT);

        $register = App::$app->database->insert('murid', ['idMurid', 'noTel', 'kLMurid'], $this);

        return $register;
    }

    public function verifyNoDuplicate(): bool
    {
        $check = self::checkDatabaseForDuplicates($this->idMurid);
        if (!$check) {
            $this->addError(false, 'idMurid', self::RULE_UNIQUE);
        }
        return $check;
    }

    public static function checkDatabaseForDuplicates(string $idMurid): bool
    {
        $user = App::$app->database->findOne('murid', ['idMurid' => $idMurid], class: User::class);
        if ($user instanceof User) {
            return false;
        } else {
            return true;
        }
    }
}