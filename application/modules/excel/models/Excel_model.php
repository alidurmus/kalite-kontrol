<?php

class Excel_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'excel';
    }

    public function girdi_kontrol()
    {
        $query = $this->db->query('SELECT gk.*, ml.adi as malzeme_adi, td.adi as tedarikci_adi FROM girdi_kontrol gk INNER JOIN malzemeler ml ON ml.id = gk.malzeme INNER JOIN tedarikciler td ON td.id = gk.tedarikci   LIMIT 10000');

        return $query->result();
    }

    public function final_kontrol()
    {
        $query = $this->db->query('SELECT fk.*, ur.adi as urun_adi FROM final_kontrol fk INNER JOIN urunler ur ON ur.id = fk.urun  LIMIT 10000');

        return $query->result();
    }

    public function proses_kontrol()
    {
        $query = $this->db->query('SELECT pk.*, ur.adi as urun_adi FROM proses_kontrol pk INNER JOIN urunler ur ON ur.id = pk.urun   LIMIT 10000');

        return $query->result();
    }
}
