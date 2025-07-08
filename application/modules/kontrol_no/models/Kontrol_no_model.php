<?php

class Kontrol_no_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->tableName = 'kontrol_no';
    }

    public function get_limit($where = [], $limit, $start, $order_by = 'id DESC')
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        // Apply where conditions
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (strpos($key, 'LIKE') !== false) {
                    $field = str_replace(' LIKE', '', $key);
                    $this->db->like($field, $value);
                } elseif (strpos($key, 'DATE(') !== false || strpos($key, '>=') !== false || strpos($key, '<=') !== false || strpos($key, '>') !== false || strpos($key, '<') !== false) {
                    $this->db->where($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }

        // Order by
        if ($order_by) {
            $order_parts = explode(' ', $order_by);
            $field = $order_parts[0];
            $direction = $order_parts[1] ?? 'ASC';
            $this->db->order_by($field, $direction);
        }

        // Limit and offset
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        return $query->result();
    }

    // Get total count with where conditions
    public function get_count($where = [])
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from($this->tableName);

        // Apply where conditions
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (strpos($key, 'LIKE') !== false) {
                    $field = str_replace(' LIKE', '', $key);
                    $this->db->like($field, $value);
                } elseif (strpos($key, 'DATE(') !== false || strpos($key, '>=') !== false || strpos($key, '<=') !== false || strpos($key, '>') !== false || strpos($key, '<') !== false) {
                    $this->db->where($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }

        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->count : 0;
    }

    // Select total records (legacy method for backward compatibility)
    public function getrecordCount($search = '')
    {
        $this->db->select('count(*) as allcount');
        $this->db->from($this->tableName);
        if ($search != '') {
            $this->db->like('process_isim', $search);
        }
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
}
