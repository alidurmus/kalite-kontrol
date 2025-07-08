<?php

class Proses_kontrol_model extends MY_Model
{
    protected $measurement_service;

    public function __construct()
    {
        parent::__construct();
        
        // Load security helper for XSS protection
        $this->load->helper('security');
        
        $this->tableName = 'proses_kontrol';

        // MeasurementDataService'i yükle
        $CI = &get_instance();
        if (!isset($CI->measurementdataservice)) {
            $CI->load->library('MeasurementDataService');
        }
        $this->measurement_service = $CI->measurementdataservice;
    }

    public function get_limit($where = [], $limit, $start, $search = '')
    {
        // $this->db->limit($limit, $start);
        //$query = $this->db->where($where)->get($this->tableName);
        /*
         $query = $this->db->query(
            'SELECT pk.*,
            ur.adi as urun_adi,
            us.user_name as kullanici_adi,
            son.adi as sonuc_adi
            FROM proses_kontrol pk
            INNER JOIN urunler ur ON ur.id = pk.urun
            INNER JOIN users us ON us.id = pk.kullanici
            INNER JOIN sonuc_secim son ON son.id = pk.sonuc

             ORDER BY pk.id DESC
             LIMIT '. $limit.' OFFSET '. $start.'
              ');
        */

        $this->db->select('pk.id, pk.urun,pk.lot,pk.kontrol_no,pk.tarih,pk.parti_no,pk.hpk,   
        ur.adi as urun_adi, 
        us.user_name as kullanici_adi , 
        son.adi as sonuc_adi');
        $this->db->from('proses_kontrol pk');
        $this->db->join('urunler ur', 'ur.id = pk.urun', 'inner');
        $this->db->join('users us', 'us.id = pk.kullanici', 'inner');
        $this->db->join('sonuc_secim son', 'son.id = pk.sonuc', 'inner');
        $this->db->order_by('pk.id ', 'DESC');
        $this->db->limit($limit, $start);
        if ($search != '') {
            $this->db->like('ur.adi', $search);
            $this->db->or_like('pk.lot', $search);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /*
<td><?php echo safe_output($item->urun_adi); ?></td>
<td><?php echo safe_output($item->lot); ?></td>
<td><?php echo safe_output($item->kontrol_no); ?></td>
<td><?php echo safe_output($item->parti_no); ?></td>
<td><?php  echo tarih_ayarla($item->tarih,"Y/m/d H:i");  ?></td>

     */

    public function listele()
    {
        $query = $this->db->query(
            'SELECT pk.id, pk.urun,pk.lot,pk.kontrol_no,pk.tarih,pk.parti_no, pk.buji_braket_lot,
            pk.aciklama,pk.hpk,pk.klips_hpk,   
        ur.adi as urun_adi, 
        us.user_name as kullanici_adi, 
        son.adi as sonuc_adi  
        FROM proses_kontrol pk   
        INNER JOIN urunler ur ON ur.id = pk.urun 
        INNER JOIN users us ON us.id = pk.kullanici 
        INNER JOIN sonuc_secim son ON son.id = pk.sonuc 
		ORDER BY pk.tarih DESC
        LIMIT 10000
        '
        );

        return $query->result();
    }

    // Select total records
    public function getrecordCount($search = '')
    {
        $this->db->select('count(*) as allcount, pk.id, 
        ur.adi as urun_adi, 
        us.user_name as kullanici_adi , 
        son.adi as sonuc_adi');
        $this->db->from('proses_kontrol pk');
        $this->db->join('urunler ur', 'ur.id = pk.urun', 'inner');
        $this->db->join('users us', 'us.id = pk.kullanici', 'inner');
        $this->db->join('sonuc_secim son', 'son.id = pk.sonuc', 'inner');
        if ($search != '') {
            $this->db->like('ur.adi', $search);
            $this->db->or_like('pk.lot', $search);
        }

        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    public function getSearchCount($search = '')
    {
        echo safe_output($search);
        $this->db->select('count(*) as allcount, pk.id, pk.lot,
        ur.adi as urun_adi, 
        us.user_name as kullanici_adi , 
        son.adi as sonuc_adi');
        $this->db->from('proses_kontrol pk');
        $this->db->join('urunler ur', 'ur.id = pk.urun', 'inner');
        $this->db->join('users us', 'us.id = pk.kullanici', 'inner');
        $this->db->join('sonuc_secim son', 'son.id = pk.sonuc', 'inner');
        if ($search != '') {
            $this->db->like('pk.lot', $search);
        }

        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    public function getSearch($where = [], $search = '')
    {
        $this->db->select('pk.id, pk.urun,pk.lot,pk.kontrol_no,pk.tarih,pk.parti_no,  
        ur.adi as urun_adi, 
        us.user_name as kullanici_adi , 
        son.adi as sonuc_adi');
        $this->db->from('proses_kontrol pk');
        $this->db->join('urunler ur', 'ur.id = pk.urun', 'inner');
        $this->db->join('users us', 'us.id = pk.kullanici', 'inner');
        $this->db->join('sonuc_secim son', 'son.id = pk.sonuc', 'inner');
        $this->db->limit(1000);
        $this->db->order_by('pk.id ', 'DESC');
        if ($search != '') {
            $this->db->like('pk.lot', $search);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Kayıt ekle (ölçüm verisi ile birlikte).
     *
     * @param array $data Kayıt verisi
     * @param array $measurement_data Ölçüm verisi
     * @return int|false Eklenen kayıt ID'si veya false
     */
    public function add_with_measurement($data, $measurement_data = null)
    {
        try {
            $this->db->trans_start();

            // Kayıt ekle
            $insert_id = $this->add($data);

            if (!$insert_id) {
                throw new Exception('Failed to insert record');
            }

            // Ölçüm verisi varsa dosyaya kaydet
            if (!empty($measurement_data)) {
                $filename = $this->measurement_service->save_measurement_data(
                    'proses_kontrol',
                    $insert_id,
                    $measurement_data
                );

                if ($filename) {
                    // Kayıt güncelle
                    $this->update(
                        ['id' => $insert_id],
                        [
                            'measurement_file' => $filename,
                            'measurement_checksum' => md5(json_encode($measurement_data)),
                        ]
                    );
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction failed');
            }

            return $insert_id;
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::add_with_measurement() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Kayıt güncelle (ölçüm verisi ile birlikte).
     *
     * @param array $where WHERE koşulları
     * @param array $data Güncellenecek veri
     * @param array $measurement_data Ölçüm verisi
     * @return bool Başarı durumu
     */
    public function update_with_measurement($where, $data, $measurement_data = null)
    {
        try {
            $this->db->trans_start();

            // Mevcut kaydı bul
            $existing_record = $this->get($where);
            if (!$existing_record) {
                throw new Exception('Record not found');
            }

            // Ölçüm verisi varsa dosyaya kaydet
            if (!empty($measurement_data)) {
                // Eski dosyayı sil (eğer varsa)
                if (!empty($existing_record->measurement_file)) {
                    $this->measurement_service->delete_measurement_data($existing_record->measurement_file);
                }

                // Yeni dosya kaydet
                $filename = $this->measurement_service->save_measurement_data(
                    'proses_kontrol',
                    $existing_record->id,
                    $measurement_data
                );

                if ($filename) {
                    $data['measurement_file'] = $filename;
                    $data['measurement_checksum'] = md5(json_encode($measurement_data));
                }
            }

            // Kayıt güncelle
            $result = $this->update($where, $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction failed');
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::update_with_measurement() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Ölçüm verisi ile birlikte kayıt ekle veya güncelle (Hibrit Method).
     * Bu method controller'dan çağrılır ve ID parametresine göre add veya update yapar.
     *
     * @param array $data Kayıt verisi
     * @param array $measurement_data Ölçüm verisi (JSON formatında)
     * @param int|null $id Güncelleme için kayıt ID'si (null ise yeni kayıt)
     * @return int|bool Eklenen kayıt ID'si (insert için) veya bool (update için)
     */
    public function save_with_measurement($data, $measurement_data = null, $id = null)
    {
        try {
            if ($id) {
                // UPDATE işlemi
                log_message('info', "Proses_kontrol_model::save_with_measurement() - Updating record ID: {$id}");
                
                $where = ['id' => $id];
                $result = $this->update_with_measurement($where, $data, $measurement_data);
                
                if ($result) {
                    log_message('info', "Proses_kontrol_model::save_with_measurement() - Successfully updated record ID: {$id}");
                    return true;
                } else {
                    log_message('error', "Proses_kontrol_model::save_with_measurement() - Failed to update record ID: {$id}");
                    return false;
                }
            } else {
                // INSERT işlemi
                log_message('info', 'Proses_kontrol_model::save_with_measurement() - Creating new record');
                
                $insert_id = $this->add_with_measurement($data, $measurement_data);
                
                if ($insert_id) {
                    log_message('info', "Proses_kontrol_model::save_with_measurement() - Successfully created record ID: {$insert_id}");
                    return $insert_id;
                } else {
                    log_message('error', 'Proses_kontrol_model::save_with_measurement() - Failed to create new record');
                    return false;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::save_with_measurement() - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kayıt ile birlikte ölçüm verisini getir.
     *
     * @param array $where WHERE koşulları
     * @return object|false Kayıt verisi veya false
     */
    public function get_with_measurement($where)
    {
        try {
            $record = $this->get($where);

            if (!$record) {
                return false;
            }

            // Ölçüm verisini dosyadan yükle (migration sonrası olcum_file_path alanından)
            if (!empty($record->olcum_file_path)) {
                // olcum_file_path'den sadece dosya adını çıkar
                $filename = basename($record->olcum_file_path);
                $measurement_data = $this->measurement_service->load_measurement_data($filename);
                $record->olcum_data = $measurement_data;

                // Geriye uyumluluk için JSON string olarak da ekle
                if ($measurement_data) {
                    $record->olcum = json_encode($measurement_data);
                }
            } else {
                // Migration tamamlandı - olcum alanı artık veritabanında yok
                // Eğer migration_file yoksa boş veri döndür
                $record->olcum_data = null;
                $record->olcum = null;
            }

            return $record;
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::get_with_measurement() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Kayıt sil (ölçüm dosyası ile birlikte).
     *
     * @param array $where WHERE koşulları
     * @return bool Başarı durumu
     */
    public function delete_with_measurement($where)
    {
        try {
            $this->db->trans_start();

            // Mevcut kaydı bul
            $existing_record = $this->get($where);
            if (!$existing_record) {
                log_message('warning', 'Record not found for deletion');

                return true; // Kayıt zaten yok
            }

            // Ölçüm dosyasını sil (eğer varsa)
            if (!empty($existing_record->measurement_file)) {
                $this->measurement_service->delete_measurement_data($existing_record->measurement_file);
            }

            // Kayıt sil
            $result = $this->delete($where);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction failed');
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::delete_with_measurement() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Eski JSON verilerini dosya sistemine migrate et.
     *
     * @param int $limit Bir seferde işlenecek kayıt sayısı
     * @return array Migrate istatistikleri
     */
    public function migrate_json_to_files($limit = 100)
    {
        $stats = [
            'processed' => 0,
            'migrated' => 0,
            'errors' => 0,
            'skipped' => 0,
        ];

        try {
            // Migration artık tamamlandı - olcum alanı artık veritabanında yok
            log_message('info', 'migrate_json_to_files: Migration already completed, olcum column removed');
            
            // Mevcut migration durumunu kontrol et
            $this->db->select('COUNT(*) as total, SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) as migrated');
            $query = $this->db->get($this->tableName);
            $result = $query->row();
            
            $stats['processed'] = $result->total;
            $stats['migrated'] = $result->migrated;
            
            log_message('info', "proses_kontrol migration status: {$stats['migrated']}/{$stats['processed']} records migrated");
            
        } catch (Exception $e) {
            log_message('error', 'Proses_kontrol_model::migrate_json_to_files() - ' . $e->getMessage());
        }

        return $stats;
    }
}
