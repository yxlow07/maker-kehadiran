<?php

namespace app\Models;

use core\App;
use core\Models\ValidationModel;

class LoginModel extends ValidationModel
{
    public string $idMurid = '';
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
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'password' => [self::RULE_REQUIRED],
            'rememberMe' => []
        ];
    }

    public function fieldNames(): array
    {
        return [
            'idMurid' => 'ID Murid',
            'password' => 'password'
        ];
    }

    public function verifyUser(bool $isLogin = true): bool
    {
        /** @var UserModel $user */
        $user = self::getUserFromDB($this->idMurid);

        if ($isLogin) {
            if (!$user) {
                $this->addError(false, 'idMurid', self::RULE_MATCH, ['match', 'must be a valid existing ID']);
                return false;
            }

            if (!password_verify($this->password, $user->kLMurid)) {
                $this->addError(false, 'password', self::RULE_MATCH, ['match', 'is incorrect']);
                return false;
            }
        }

        App::$app->user = $user;
        $user->getNameFromDatabase();

        return true;
    }

    public static function getUserFromDB(string $idMurid)
    {
        return App::$app->database->findOne('murid', conditions: ['idMurid' => $idMurid], class: UserModel::class);
    }

    public static function setNewUpdatedUserData(string $idMurid)
    {
        $user = self::getUserFromDB($idMurid);

        App::$app->user = $user;
        App::$app->user->getNameFromDatabase();
        App::$app->session->set('user', App::$app->user);
    }

    public function getUserData(): array
    {
        return $this->userData;
    }
}