<?php

class Settings_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'settings';
    }

    public function get($where = [])
    {
        // Eğer where array boşsa, ilk settings kaydını object olarak döndür
        if (empty($where)) {
            $result = $this->db->get($this->tableName)->row();

            if ($result) {
                // Veritabanından gelen ayarları kullan
                return $result;
            } else {
                // Eğer hiç kayıt yoksa default değerlerle yeni bir object oluştur
                $settings_object = new stdClass();
                $settings_object->id = 1;
                $settings_object->company_name = 'Kalite Yönetim Sistemi';
                $settings_object->logo = 'default';
                $settings_object->favicon = 'default';
                $settings_object->slogan = '';
                $settings_object->address = '';
                $settings_object->phone_1 = '';
                $settings_object->email = '';

                return $settings_object;
            }
        }

        // Normal where koşulu varsa parent get fonksiyonunu kullan
        return parent::get($where);
    }
}
