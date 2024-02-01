<?php

namespace app\Models;

use core\App;

class User
{
    public string $idMurid = '';
    public string $noTel = '';
    public string $kLMurid = '';
    public string $infoLogMasuk = '';
    public ?array $infoMurid = [];

    public function __construct()
    {
        $this->infoMurid = json_decode($this->infoLogMasuk, true);
    }

    public function setCookies(): void
    {
        $sessionID = App::$app->session->generateSessionID();

        setcookie('idMurid', $this->idMurid, time() + 2630000);
        setcookie('sessionID', $sessionID, time() + 2630000);

        $this->infoMurid['sessionID'] = $sessionID;
        App::$app->database->update('murid', ['infoLogMasuk'], ['infoLogMasuk' => json_encode($this->infoMurid)], ['idMurid' => $this->idMurid]);
    }

    public function isLogin(): bool
    {
        return !empty($this->idMurid);
    }
}