<?php

namespace app\Middleware;

use app\Models\UserModel;
use core\App;
use core\Exceptions\MiddlewareException;

class AuthMiddleware
{
    public function loginWithCookies()
    {
        return true;
    }

    public function isCookiesSet(): void
    {
        if (App::$app->user instanceof UserModel) return;

        $id = $_COOKIE['idMurid'] ?? null;
        $sessionId = $_COOKIE['sessionID'] ?? null;

        if (is_null($id) || is_null($sessionId)) return;

        // Session ID and murid ID is set, can check from database
        /** @var UserModel $user */
        $user = App::$app->database->findOne('murid', conditions: ['idMurid' => $id], class: UserModel::class);

        if ($user->infoMurid['sessionID'] !== $sessionId) {
            unset($_COOKIE['sessionID']);
            unset($_COOKIE['idMurid']);
            return;
        }

        App::$app->session->set('user', $user);
    }
}