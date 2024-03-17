<?php

namespace app\Models;

use core\App;
use core\Models\ValidationModel;

class AdminModel extends ValidationModel
{
    public string $idAdmin = '';
    public string $kLAdmin = '';
    public string $namaA = '';
    public bool $isAdmin = true;

    public function __construct(array $data = [])
    {
        parent::loadData($data);
    }

    public function newAdminRules(): array
    {
        return [
            'idAdmin' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'namaA' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'kLAdmin' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
        ];
    }

    public function rules(): array
    {
        return [
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'rememberMe' => []
        ];
    }

    public function fieldNames(): array
    {
        return [
            'idMurid' => 'ID Murid',
            'password' => 'Password',
            'idAdmin' => 'ID Admin',
            'namaA' => 'Nama admin',
            'kLAdmin' => 'Kata Laluan Admin',
        ];
    }

    public function isLogin(): bool
    {
        return !empty($this->idAdmin);
    }

    public function verifyNoDuplicate()
    {
        $check = $this->checkForDuplicates($this->idAdmin);

        if (!$check) {
            $this->addError(false, 'idAdmin', self::RULE_UNIQUE);
        }

        return $check;
    }

    public function updateDatabase(): bool
    {
        $this->kLAdmin = password_hash($this->kLAdmin, PASSWORD_BCRYPT);
        
        return App::$app->database->insert('admin', ['idAdmin', 'namaA', 'kLAdmin'], $this);
    }

    public function checkForDuplicates(string $idAdmin): bool
    {
        $admin = App::$app->database->findOne('admin', ['idAdmin' => $idAdmin], class: AdminModel::class);

        if ($admin instanceof AdminModel) {
            return false;
        } else {
            return true;
        }
    }
}