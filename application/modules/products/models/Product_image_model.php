<?php

class Product_image_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'product_images';
    }
}
