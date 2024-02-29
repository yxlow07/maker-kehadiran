<?php

namespace app\Models;

use core\App;
use core\Models\ValidationModel;

class ProfileModel extends ValidationModel
{
    public string $idMurid = '';
    public string $noTel = '';
    public string $namaM = '';

    public function __construct($data)
    {
        if (empty($data)) {
            $data = (array) App::$app->session->get('user');
        }

        parent::loadData($data);
    }

    public function rules(): array
    {
        return [
            'idMurid' => [self::RULE_REQUIRED],
            'noTel' => [self::RULE_REQUIRED],
            'namaM' => [self::RULE_REQUIRED],
        ];
    }

    public function fieldNames(): array
    {
        return [
            'idMurid' => 'ID Murid',
            'noTel' => 'No Telefon',
            'namaM' => 'Nama'
        ];
    }

    public function verifyNoDuplicate(): bool
    {
        if ($this->idMurid == App::$app->session->get('user')->idMurid) {
            return true;
        } else {
            $check = RegisterModel::checkDatabaseForDuplicates($this->idMurid);
            if (!$check) {
                $this->addError(false, 'idMurid', self::RULE_UNIQUE);
            }
            return $check;
        }
    }

    public function updateDatabase(): bool
    {
        $update = App::$app->database->update('murid', ['idMurid', 'noTel'], $this, ['idMurid' => App::$app->user->idMurid]);
        $updateNama = App::$app->database->update('telefon', ['noTel', 'namaM'], $this, ['noTel' => App::$app->user->noTel], true);

        return $update && $updateNama;
    }
}