<?php

namespace app\Models;

use core\Models\ValidationModel;

class AdminModel extends ValidationModel
{
    public string $idAdmin = '';
    public string $kLAdmin = '';
    public string $namaA = '';
    public bool $isAdmin = true;

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
            'password' => 'Password'
        ];
    }

    public function isLogin(): bool
    {

        return !empty($this->idAdmin);
    }
}