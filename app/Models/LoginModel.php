<?php

namespace app\Models;

use core\Models\ValidationModel;

class LoginModel extends ValidationModel
{
    public string $usernameOrEmail = '';
    public string $password = '';
    public bool $rememberMe = false;
    public array $userData = [];
    
    public function __construct(array $data)
    {
        parent::loadData($data);
    }

    public function rules(): array
    {
        return [
            'usernameOrEmail' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'password' => [self::RULE_REQUIRED],
            'rememberMe' => []
        ];
    }

    public function fieldNames(): array
    {
        return [
            'usernameOrEmail' => 'username or email',
            'password' => 'password'
        ];
    }

    public function verifyUser()
    {
        return true;
    }

    public function getUserData(): array
    {
        return $this->userData;
    }
}