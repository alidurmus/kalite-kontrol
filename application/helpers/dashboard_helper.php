<?php
/**
 * Dashboard Helper Functions
 * Provides reusable functions for dashboard content rendering
 * 
 * @package    CodeIgniter
 * @subpackage Helpers
 * @category   Dashboard
 * @author     QMS Development Team
 * @version    1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('render_stat_card')) {
    /**
     * Render a statistics card with consistent styling
     * 
     * @param array $data Card data array
     * @return string HTML output
     */
    function render_stat_card($data = []) {
        $defaults = [
            'number' => 0,
            'label' => 'Veri Yok',
            'icon' => 'zmdi-chart',
            'color' => 'primary',
            'today_text' => 'Bugün',
            'today_value' => 0,
            'link_url' => '#',
            'link_text' => 'Detayları Görüntüle',
            'animation_delay' => '0'
        ];
        
        $card = array_merge($defaults, $data);
        
        // Sanitize output for XSS protection
        $card['number'] = (int) $card['number'];
        $card['label'] = htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8');
        $card['today_text'] = htmlspecialchars($card['today_text'], ENT_QUOTES, 'UTF-8');
        $card['today_value'] = (int) $card['today_value'];
        $card['link_text'] = htmlspecialchars($card['link_text'], ENT_QUOTES, 'UTF-8');
        $card['link_url'] = htmlspecialchars($card['link_url'], ENT_QUOTES, 'UTF-8');
        
        ob_start();
        ?>
        <div class="stats-card stats-card-<?php echo $card['color']; ?>" data-aos="fade-up" data-aos-delay="<?php echo $card['animation_delay']; ?>">
            <div class="stats-icon">
                <i class="zmdi <?php echo $card['icon']; ?>"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number" data-target="<?php echo $card['number']; ?>">0</div>
                <div class="stats-label"><?php echo $card['label']; ?></div>
                <div class="stats-today">
                    <i class="zmdi zmdi-trending-up"></i>
                    <?php echo $card['today_text']; ?>: <strong><?php echo $card['today_value']; ?></strong>
                </div>
            </div>
            <div class="stats-action">
                <a href="<?php echo $card['link_url']; ?>" class="stats-link">
                    <?php echo $card['link_text']; ?> <i class="zmdi zmdi-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('render_mini_stat_card')) {
    /**
     * Render a mini statistics card
     * 
     * @param array $data Card data array
     * @return string HTML output
     */
    function render_mini_stat_card($data = []) {
        $defaults = [
            'number' => 0,
            'label' => 'Veri Yok',
            'icon' => 'zmdi-chart',
            'color' => '#007bff',
            'animation_delay' => '0'
        ];
        
        $card = array_merge($defaults, $data);
        
        // Sanitize output
        $card['number'] = (int) $card['number'];
        $card['label'] = htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8');
        $card['color'] = htmlspecialchars($card['color'], ENT_QUOTES, 'UTF-8');
        
        ob_start();
        ?>
        <div class="mini-stats-card" data-aos="fade-up" data-aos-delay="<?php echo $card['animation_delay']; ?>">
            <div class="mini-stats-icon" style="background-color: <?php echo $card['color']; ?>;">
                <i class="zmdi <?php echo $card['icon']; ?>"></i>
            </div>
            <div class="mini-stats-content">
                <h4><?php echo number_format($card['number']); ?></h4>
                <span><?php echo $card['label']; ?></span>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('render_quick_action')) {
    /**
     * Render a quick action button
     * 
     * @param array $data Action data array
     * @return string HTML output
     */
    function render_quick_action($data = []) {
        $defaults = [
            'title' => 'Aksiyon',
            'icon' => 'zmdi-plus',
            'color' => 'primary',
            'url' => '#',
            'animation_delay' => '0'
        ];
        
        $action = array_merge($defaults, $data);
        
        // Sanitize output
        $action['title'] = htmlspecialchars($action['title'], ENT_QUOTES, 'UTF-8');
        $action['url'] = htmlspecialchars($action['url'], ENT_QUOTES, 'UTF-8');
        $action['color'] = htmlspecialchars($action['color'], ENT_QUOTES, 'UTF-8');
        
        ob_start();
        ?>
        <div class="quick-action-item" data-color="<?php echo $action['color']; ?>" data-aos="zoom-in" data-aos-delay="<?php echo $action['animation_delay']; ?>">
            <a href="<?php echo $action['url']; ?>" class="quick-action-link">
                <div class="quick-action-icon">
                    <i class="zmdi <?php echo $action['icon']; ?>"></i>
                </div>
                <div class="quick-action-text"><?php echo $action['title']; ?></div>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('render_activity_item')) {
    /**
     * Render an activity list item
     * 
     * @param array $data Activity data array
     * @return string HTML output
     */
    function render_activity_item($data = []) {
        $defaults = [
            'time' => date('Y-m-d H:i:s'),
            'title' => 'Aktivite',
            'description' => 'Açıklama yok',
            'type' => 'info',
            'user' => 'Sistem'
        ];
        
        $activity = array_merge($defaults, $data);
        
        // Sanitize output
        $activity['title'] = htmlspecialchars($activity['title'], ENT_QUOTES, 'UTF-8');
        $activity['description'] = htmlspecialchars($activity['description'], ENT_QUOTES, 'UTF-8');
        $activity['user'] = htmlspecialchars($activity['user'], ENT_QUOTES, 'UTF-8');
        
        // Format time
        $formatted_time = format_time_display($activity['time']);
        
        ob_start();
        ?>
        <div class="activity-item">
            <div class="activity-time">
                <small class="text-muted"><?php echo $formatted_time; ?></small>
            </div>
            <div class="activity-content">
                <strong><?php echo $activity['title']; ?></strong><br>
                <small><?php echo $activity['description']; ?> - <em><?php echo $activity['user']; ?></em></small>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('format_time_display')) {
    /**
     * Format time for display in dashboard
     * 
     * @param string $datetime DateTime string
     * @param string $format Display format
     * @return string Formatted time
     */
    function format_time_display($datetime, $format = 'd.m.Y H:i') {
        if (empty($datetime)) {
            return 'Bilinmiyor';
        }
        
        try {
            $date = new DateTime($datetime);
            return $date->format($format);
        } catch (Exception $e) {
            return 'Geçersiz tarih';
        }
    }
}

if (!function_exists('get_color_by_status')) {
    /**
     * Get color class based on status
     * 
     * @param string $status Status value
     * @return string Color class
     */
    function get_color_by_status($status) {
        $status = strtoupper(trim($status));
        
        switch ($status) {
            case 'APPROVED':
            case 'KABUL':
            case 'ONAYLANDI':
                return 'success';
            case 'REJECTED':
            case 'RED':
            case 'REDDEDILDI':
                return 'danger';
            case 'PENDING':
            case 'BEKLIYOR':
            case 'INCELENIYOR':
                return 'warning';
            case 'PROCESSING':
            case 'ISLENIYOR':
                return 'info';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('generate_chart_data')) {
    /**
     * Generate chart data array from database results
     * 
     * @param array $data Raw data from database
     * @param string $label_field Field name for labels
     * @param string $value_field Field name for values
     * @return array Chart-ready data
     */
    function generate_chart_data($data, $label_field = 'label', $value_field = 'value') {
        if (empty($data) || !is_array($data)) {
            return [
                'labels' => [],
                'values' => [],
                'colors' => []
            ];
        }
        
        $default_colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6c757d'];
        $labels = [];
        $values = [];
        $colors = [];
        
        foreach ($data as $index => $item) {
            $labels[] = htmlspecialchars($item[$label_field] ?? "Item $index", ENT_QUOTES, 'UTF-8');
            $values[] = (int) ($item[$value_field] ?? 0);
            $colors[] = $default_colors[$index % count($default_colors)];
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];
    }
}

if (!function_exists('render_performance_metric')) {
    /**
     * Render a performance metric progress bar
     * 
     * @param array $data Metric data array
     * @return string HTML output
     */
    function render_performance_metric($data = []) {
        $defaults = [
            'label' => 'Metrik',
            'percentage' => 0,
            'color' => 'primary'
        ];
        
        $metric = array_merge($defaults, $data);
        
        // Sanitize and validate
        $metric['label'] = htmlspecialchars($metric['label'], ENT_QUOTES, 'UTF-8');
        $metric['percentage'] = max(0, min(100, (int) $metric['percentage']));
        
        $color_class = '';
        switch ($metric['color']) {
            case 'success': $color_class = 'bg-success'; break;
            case 'warning': $color_class = 'bg-warning'; break;
            case 'danger': $color_class = 'bg-danger'; break;
            case 'info': $color_class = 'bg-info'; break;
            default: $color_class = 'bg-primary';
        }
        
        ob_start();
        ?>
        <div class="performance-metric">
            <div class="metric-label"><?php echo $metric['label']; ?></div>
            <div class="metric-progress">
                <div class="progress">
                    <div class="progress-bar <?php echo $color_class; ?>" 
                         role="progressbar" 
                         style="width: <?php echo $metric['percentage']; ?>%" 
                         aria-valuenow="<?php echo $metric['percentage']; ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <div class="metric-percentage"><?php echo $metric['percentage']; ?>%</div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

/* End of file dashboard_helper.php */
/* Location: ./application/helpers/dashboard_helper.php */ 