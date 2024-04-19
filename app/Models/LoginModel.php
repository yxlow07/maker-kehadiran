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
            'idMurid' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 10]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 15]],
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

    public function verifyUser(): bool
    {
        /** @var UserModel|AdminModel $user */
        $user = self::getUserFromDB($this->idMurid);

        if (!$user) {
            $user = self::getAdminFromDB($this->idMurid);
            if (!$user) {
                $this->addError(false, 'idMurid', self::RULE_MATCH, ['match', 'must be a valid existing ID']);
                return false;
            }
        }

        $checkedPassword = $user->kLMurid ?? $user->kLAdmin;

        if (!password_verify($this->password, $checkedPassword)) {
            $this->addError(false, 'password', self::RULE_MATCH, ['match', 'is incorrect']);
            return false;
        }

        App::$app->user = $user;

        if (!$user->isAdmin) {
            $user->getNameFromDatabase();
        }

        return true;
    }

    public static function getUserFromDB(string $idMurid, bool $getName = false): UserModel|false
    {
        /** @var UserModel|false $user */
        $user = App::$app->database->findOne('murid', conditions: ['idMurid' => $idMurid], class: UserModel::class);

        if ($getName && $user instanceof UserModel) {
            $user->getNameFromDatabase();
        }

        return $user;
    }

    public static function getAdminFromDB(string $idAdmin)
    {
        return App::$app->database->findOne('admin', conditions: ['idAdmin' => $idAdmin], class: AdminModel::class);
    }

    public static function setNewUpdatedUserData(string $idMurid): void
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