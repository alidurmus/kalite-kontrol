<?php

class Urunler_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'urunler';
    }

    public function get_count($where = [])
    {
        return $this->db->where($where)->count_all_results($this->tableName);
    }

    public function get_limit($where = [], $limit, $start, $order_by = 'id DESC')
    {
        $this->db->where($where)->limit($limit, $start)->order_by($order_by);

        return $this->db->get($this->tableName)->result();
    }
}
