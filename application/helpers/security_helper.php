<?php
/**
 * Security Helper for QMS - Enhanced XSS Protection.
 *
 * Bu helper XSS saldırılarına karşı kapsamlı koruma sağlar
 */
if (!function_exists('safe_output')) {
    /**
     * Güvenli output için ana fonksiyon
     * Tüm echo işlemleri için kullanılmalıdır.
     */
    function safe_output($data, $double_encode = false)
    {
        if ($data === null || $data === '') {
            return '';
        }

        return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8', $double_encode);
    }
}

if (!function_exists('safe_attr')) {
    /**
     * HTML attribute'ları için güvenli output
     * Form input value'ları için kullanılır.
     */
    function safe_attr($data)
    {
        if ($data === null || $data === '') {
            return '';
        }

        // Remove potential XSS vectors from attributes
        $data = str_replace(['javascript:', 'vbscript:', 'onload', 'onerror', 'onclick', 'onmouseover'], '', $data);

        return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sanitize_search')) {
    /**
     * Search input'ları için sanitization
     * Arama formlarında kullanılır.
     */
    function sanitize_search($search_term)
    {
        if ($search_term === null || $search_term === '') {
            return '';
        }

        // Remove all HTML tags but keep text content
        $search_term = strip_tags($search_term);

        // Trim and limit length
        $search_term = trim($search_term);
        if (strlen($search_term) > 100) {
            $search_term = substr($search_term, 0, 100);
        }

        return $search_term;
    }
}

if (!function_exists('safe_url')) {
    /**
     * URL'ler için güvenli output
     * href, src gibi URL attribute'ları için kullanılır.
     */
    function safe_url($url)
    {
        if (empty($url)) {
            return '';
        }

        // URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL) && !preg_match('/^\//', $url)) {
            return '#'; // Invalid URL'ler için güvenli fallback
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('safe_js')) {
    /**
     * JavaScript string'leri için güvenli output
     * JS içinde kullanılan string'ler için.
     */
    function safe_js($data)
    {
        if ($data === null || $data === '') {
            return '';
        }

        return json_encode((string) $data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}

if (!function_exists('safe_html')) {
    /**
     * HTML content için güvenli output
     * Summernote gibi rich text editor'lar için.
     */
    function safe_html($html)
    {
        if (empty($html)) {
            return '';
        }

        // Basit HTML purifier - production'da HTMLPurifier kullanılmalı
        $allowed_tags = '<p><br><strong><b><em><i><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6>';

        return strip_tags($html, $allowed_tags);
    }
}

if (!function_exists('validate_input')) {
    /**
     * Input validation fonksiyonu
     * Tüm POST/GET verileri için kullanılmalıdır.
     */
    function validate_input($data, $type = 'string', $max_length = 255)
    {
        if ($data === null || $data === '') {
            return '';
        }

        // Trim whitespace
        $data = trim($data);

        switch ($type) {
            case 'int':
                return (int) $data;
            case 'float':
                return (float) $data;
            case 'email':
                return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : '';
            case 'url':
                return filter_var($data, FILTER_VALIDATE_URL) ? $data : '';
            case 'string':
            default:
                // Length kontrolü
                if (strlen($data) > $max_length) {
                    $data = substr($data, 0, $max_length);
                }

                return $data;
        }
    }
}

if (!function_exists('generate_csrf_token')) {
    /**
     * CSRF token oluştur.
     */
    function generate_csrf_token()
    {
        $CI = &get_instance();

        return $CI->security->get_csrf_hash();
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * CSRF token doğrula.
     */
    function verify_csrf_token($token)
    {
        $CI = &get_instance();

        return $CI->security->csrf_verify();
    }
}

if (!function_exists('secure_filename')) {
    /**
     * Dosya adları için güvenlik.
     */
    function secure_filename($filename)
    {
        // Tehlikeli karakterleri temizle
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        // Çoklu nokta ve tire kontrolü
        $filename = preg_replace('/[._-]+/', '_', $filename);

        return $filename;
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Güvenlik olaylarını logla.
     */
    function log_security_event($event_type, $description, $user_id = null)
    {
        $CI = &get_instance();

        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event_type' => $event_type,
            'description' => $description,
            'user_id' => $user_id,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent(),
        ];

        log_message('security', json_encode($log_data));
    }
}
