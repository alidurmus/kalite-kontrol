<?php

class Portfolio_image_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'portfolio_images';
    }
}
