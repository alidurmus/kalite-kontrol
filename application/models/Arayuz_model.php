<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Arayuz_model extends MY_Model
{
    public function saveGirdiForm($data = [])
    {
        $data = [
            'malzeme' => $data['malzeme'],
            'tedarikci' => $data['tedarikci'],
            'irsaliye' => $data['irsaliye'],
            'tarih' => $data['tarih'],
            'parti_no' => $data['parti_no'],
            'kontrol_no' => $data['kontrol_no'],
            'kullanici' => $data['kullanici'],
            'sonuc' => $data['sonuc'],
            'olcum' => json_encode($data['form']),
        ];

        $this->db->insert('girdi_kontrol', $data);
    }

    public function girdiFormlar()
    {
        //$query = $this->db->get('girdi_kontrol');
        $query = $this->db->query('SELECT gk.*, ml.adi as malzeme_adi, td.adi as tedarikci_adi FROM girdi_kontrol gk INNER JOIN malzemeler ml ON ml.id = gk.malzeme INNER JOIN tedarikciler td ON td.id = gk.tedarikci');

        return $query->result();
    }

    public function girdiForm($form_id)
    {
        $query = $this->db->get_where('girdi_kontrol', ['id' => $form_id]);

        return $query->result();
    }
}

// End of file ModelName.php
