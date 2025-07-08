<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Performance Cache Library
 * 
 * Handles caching of frequently accessed data to improve performance
 * Uses file-based caching with TTL support
 */
class Performance_cache {
    
    private $cache_path;
    private $default_ttl = 300; // 5 minutes default
    
    public function __construct()
    {
        $this->cache_path = APPPATH . 'cache/performance/';
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0755, true);
        }
    }
    
    /**
     * Get cached data
     * 
     * @param string $key Cache key
     * @return mixed|false Cached data or false if not found/expired
     */
    public function get($key)
    {
        $file_path = $this->cache_path . md5($key) . '.cache';
        
        if (!file_exists($file_path)) {
            return false;
        }
        
        $data = file_get_contents($file_path);
        $cache_data = unserialize($data);
        
        // Check if expired
        if ($cache_data['expires'] < time()) {
            unlink($file_path);
            return false;
        }
        
        return $cache_data['data'];
    }
    
    /**
     * Set cache data
     * 
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int $ttl Time to live in seconds
     * @return bool Success status
     */
    public function set($key, $data, $ttl = null)
    {
        if ($ttl === null) {
            $ttl = $this->default_ttl;
        }
        
        $cache_data = [
            'data' => $data,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        $file_path = $this->cache_path . md5($key) . '.cache';
        
        return file_put_contents($file_path, serialize($cache_data)) !== false;
    }
    
    /**
     * Delete cached data
     * 
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete($key)
    {
        $file_path = $this->cache_path . md5($key) . '.cache';
        
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
        
        return true;
    }
    
    /**
     * Clear all cache
     * 
     * @return bool Success status
     */
    public function clear_all()
    {
        $files = glob($this->cache_path . '*.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return true;
    }
    
    /**
     * Get or set cache with callback
     * 
     * @param string $key Cache key
     * @param callable $callback Function to generate data if not cached
     * @param int $ttl Time to live in seconds
     * @return mixed Cached or generated data
     */
    public function remember($key, $callback, $ttl = null)
    {
        $data = $this->get($key);
        
        if ($data === false) {
            $data = call_user_func($callback);
            $this->set($key, $data, $ttl);
        }
        
        return $data;
    }
    
    /**
     * Cache dashboard statistics
     * 
     * @return array Dashboard statistics
     */
    public function get_dashboard_stats()
    {
        return $this->remember('dashboard_stats', function() {
            $CI = &get_instance();
            
            // Load models if not already loaded
            if (!isset($CI->final_kontrol_model)) {
                $CI->load->model('finalkontrol/final_kontrol_model');
            }
            if (!isset($CI->girdi_kontrol_model)) {
                $CI->load->model('girdikontrol/girdi_kontrol_model');
            }
            if (!isset($CI->proses_kontrol_model)) {
                $CI->load->model('proseskontrol/proses_kontrol_model');
            }
            
            return [
                'bugun_final' => $CI->final_kontrol_model->get_count(['DATE(tarih)' => date('Y-m-d')]),
                'bugun_girdi' => $CI->girdi_kontrol_model->get_count(['DATE(tarih)' => date('Y-m-d')]),
                'bugun_proses' => $CI->proses_kontrol_model->get_count(['DATE(tarih)' => date('Y-m-d')]),
                'hafta_final' => $CI->final_kontrol_model->get_count(['WEEK(tarih)' => date('W')]),
                'hafta_girdi' => $CI->girdi_kontrol_model->get_count(['WEEK(tarih)' => date('W')]),
                'hafta_proses' => $CI->proses_kontrol_model->get_count(['WEEK(tarih)' => date('W')]),
                'toplam_final' => $CI->final_kontrol_model->get_count(),
                'toplam_girdi' => $CI->girdi_kontrol_model->get_count(),
                'toplam_proses' => $CI->proses_kontrol_model->get_count(),
                'generated_at' => date('Y-m-d H:i:s')
            ];
        }, 300); // Cache for 5 minutes
    }
    
    /**
     * Cache user list for dropdowns
     * 
     * @return array User list
     */
    public function get_user_list()
    {
        return $this->remember('user_list', function() {
            $CI = &get_instance();
            if (!isset($CI->user_model)) {
                $CI->load->model('users/user_model');
            }
            
            return $CI->user_model->get_all(['aktif' => 1]);
        }, 600); // Cache for 10 minutes
    }
    
    /**
     * Cache product list for dropdowns
     * 
     * @return array Product list
     */
    public function get_product_list()
    {
        return $this->remember('product_list', function() {
            $CI = &get_instance();
            if (!isset($CI->urun_model)) {
                $CI->load->model('urunler/urun_model');
            }
            
            return $CI->urun_model->get_all(['aktif' => 1]);
        }, 900); // Cache for 15 minutes
    }
    
    /**
     * Invalidate related caches when data changes
     * 
     * @param string $type Type of data changed (final, girdi, proses, user, product)
     */
    public function invalidate_related($type)
    {
        switch ($type) {
            case 'final':
            case 'girdi':
            case 'proses':
                $this->delete('dashboard_stats');
                break;
            case 'user':
                $this->delete('user_list');
                $this->delete('dashboard_stats');
                break;
            case 'product':
                $this->delete('product_list');
                break;
        }
    }
    
    /**
     * Get cache statistics
     * 
     * @return array Cache statistics
     */
    public function get_stats()
    {
        $files = glob($this->cache_path . '*.cache');
        $total_size = 0;
        $expired_count = 0;
        
        foreach ($files as $file) {
            $total_size += filesize($file);
            
            $data = unserialize(file_get_contents($file));
            if ($data['expires'] < time()) {
                $expired_count++;
            }
        }
        
        return [
            'total_files' => count($files),
            'total_size_kb' => round($total_size / 1024, 2),
            'expired_files' => $expired_count,
            'cache_path' => $this->cache_path
        ];
    }
} 