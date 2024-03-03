<?php

namespace app\Models;

use core\App;
use core\Cookies;

class UserModel
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

    public static function deleteUserFromDB(string $idMurid)
    {
        $res = App::$app->database->delete('murid', ['idMurid' => $idMurid]);
        header('Content-Type: application/json');
        echo json_encode(['success' => $res]);
        exit();
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
        return null;
    }

    public static function getAttendanceFromDatabase(string $idMurid)
    {
        $result = self::checkIfAttendanceRecordExists($idMurid);
        
        if (!$result) {
            self::makeNewAttendanceRecord($idMurid);
            self::getAttendanceFromDatabase($idMurid);
        }
        
        return $result;
    }

    private static function makeNewAttendanceRecord(string $idMurid)
    {
        App::$app->database->insert('kehadiran', ['idMurid', 'idAdmin', 'kehadiran'], [$idMurid, 'A1234', json_encode([])]);
    }

    private static function checkIfAttendanceRecordExists(string $idMurid)
    {
        return App::$app->database->findOne('kehadiran', ['idMurid' => $idMurid]);
    }
}