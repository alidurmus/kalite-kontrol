<?php

//defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public $tableName;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($where = [])
    {
        return $this->db->where($where)->get($this->tableName)->row();
    }

    /** Tüm Kayıtları bana getirecek olan metot.. */
    public function get_all($where = [], $order = 'id ASC')
    {
        $this->db->limit(10000, 0);
        $query = $this->db->where($where)->order_by($order)->get($this->tableName);

        return $query->result();
    }

    public function get_limit($where = [], $limit, $start, $search = '')
    {
        $this->db->limit($limit, $start);
        $query = $this->db->where($where)->get($this->tableName);

        return $query->result();
    }

    public function search($where = [], $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->where($where)->get($this->tableName);

        // if($s_data['sepcli'] !="")
        //     $this->db->like('ts.spec_specialise',$s_data['sepcli'],'both');
        // if($s_data['distct'] !="")
        //     $this->db->like('td.district',$s_data['distct'],'both');
        // if($s_data['locat'] !="")
        //     $this->db->like('td.place', $s_data['locat'], 'both');

        return $query->result();
    }

    public function get_count()
    {
        return $this->db->count_all($this->tableName);
    }

    /**
     * Migration işlemi olup olmadığını kontrol et.
     *
     * @return bool Migration işlemi ise true
     */
    private function is_migration_context()
    {
        $CI = &get_instance();

        // Migration controller'dan çağrılıyorsa
        if (isset($CI->router) && $CI->router->class === 'Migration') {
            return true;
        }

        // Migration ile ilgili method çağrılıyorsa
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        foreach ($backtrace as $trace) {
            if (isset($trace['function']) && strpos($trace['function'], 'migrate') !== false) {
                return true;
            }
            if (isset($trace['class']) && strpos($trace['class'], 'Migration') !== false) {
                return true;
            }
        }

        return false;
    }

    public function add($data = [])
    {
        // Migration context'inde ise permission kontrolü yapma
        if ($this->is_migration_context()) {
            return $this->db->insert($this->tableName, $data);
        }

        // Normal işlemler için permission kontrolü
        if (function_exists('isAllowedWriteModule') && isAllowedWriteModule()) {
            return $this->db->insert($this->tableName, $data);
        }

        log_message('error', 'MY_Model::add() - Permission denied for table: ' . $this->tableName);

        return false;
    }

    public function update($where = [], $data = [])
    {
        // Migration context'inde ise permission kontrolü yapma
        if ($this->is_migration_context()) {
            return $this->db->where($where)->update($this->tableName, $data);
        }

        // Normal işlemler için permission kontrolü
        if (function_exists('isAllowedUpdateModule') && isAllowedUpdateModule()) {
            return $this->db->where($where)->update($this->tableName, $data);
        }

        log_message('error', 'MY_Model::update() - Permission denied for table: ' . $this->tableName);

        return false;
    }

    public function delete($where = [])
    {
        // Migration context'inde ise permission kontrolü yapma
        if ($this->is_migration_context()) {
            return $this->db->where($where)->delete($this->tableName);
        }

        // Normal işlemler için permission kontrolü
        if (function_exists('isAllowedDeleteModule') && isAllowedDeleteModule()) {
            return $this->db->where($where)->delete($this->tableName);
        }

        log_message('error', 'MY_Model::delete() - Permission denied for table: ' . $this->tableName);

        return false;
    }
}
