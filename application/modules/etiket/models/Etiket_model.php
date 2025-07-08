<?php

class Etiket_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'etiket';
    }

    public function girdi_kontrol()
    {
        $query = $this->db->query('SELECT gk.*, ml.adi as malzeme_adi, td.adi as tedarikci_adi FROM girdi_kontrol gk INNER JOIN malzemeler ml ON ml.id = gk.malzeme INNER JOIN tedarikciler td ON td.id = gk.tedarikci');

        return $query->result();
    }

    public function final_kontrol()
    {
        $query = $this->db->query('SELECT fk.*, ur.adi as urun_adi FROM final_kontrol fk INNER JOIN urunler ur ON ur.id = fk.urun');

        return $query->result();
    }

    public function proses_kontrol()
    {
        $query = $this->db->query('SELECT pk.*, ur.adi as urun_adi FROM proses_kontrol pk INNER JOIN urunler ur ON ur.id = pk.urun');

        return $query->result();
    }
}
