<?php

namespace app\Middleware;

use app\Models\User;
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
        if (App::$app->user instanceof User) return;

        $id = $_COOKIE['idMurid'] ?? null;
        $sessionId = $_COOKIE['sessionID'] ?? null;

        if (is_null($id) || is_null($sessionId)) return;

        // Session ID and murid ID is set, can check from database
        /** @var User $user */
        $user = App::$app->database->findOne('murid', conditions: ['idMurid' => $id], class: User::class);

        if ($user->infoMurid['sessionID'] !== $sessionId) {
            unset($_COOKIE['sessionID']);
            unset($_COOKIE['idMurid']);
            return;
        }

        App::$app->session->set('user', $user);
    }
}