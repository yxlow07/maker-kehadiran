<?php

namespace app\Models;

use core\App;
use core\Cookies;

class User
{
    public string $idMurid = '';
    public string $noTel = '';
    public string $kLMurid = '';
    public ?string $infoLogMasuk = '';
    public ?array $infoMurid = [];
    public mixed $namaM = '';

    public function __construct()
    {
        $this->infoMurid = json_decode($this->infoLogMasuk, true);
    }

    public function setCookies(): void
    {
        $sessionID = App::$app->session->generateSessionID();

        Cookies::setCookie('idMurid', $this->idMurid);
        Cookies::setCookie('sessionID', $sessionID);

        $this->infoMurid['sessionID'] = $sessionID;
        App::$app->database->update('murid', ['infoLogMasuk'], ['infoLogMasuk' => json_encode($this->infoMurid)], ['idMurid' => $this->idMurid]);
    }

    public function isLogin(): bool
    {
        return !empty($this->idMurid);
    }

    public function getNameFromDatabase(bool $return = false)
    {
        $this->namaM = App::$app->database->findOne('telefon', ['noTel' => $this->noTel])->namaM ?? '';

        if ($return) {
            return $this->namaM;
        }
    }
}