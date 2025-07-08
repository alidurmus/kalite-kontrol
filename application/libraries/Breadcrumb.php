<?php

/**
 * Breadcrumb Navigation Library.
 *
 * Dinamik breadcrumb navigation sistemi
 */
class Breadcrumb
{
    private $CI;

    private $breadcrumbs = [];

    private $separator = '<i class="fas fa-chevron-right"></i>';

    private $homeUrl = '';

    private $homeTitle = 'Ana Sayfa';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->homeUrl = base_url();
    }

    /**
     * Set home page details.
     */
    public function setHome($title, $url = '')
    {
        $this->homeTitle = $title;
        if (!empty($url)) {
            $this->homeUrl = $url;
        }

        return $this;
    }

    /**
     * Set separator.
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Add breadcrumb item.
     */
    public function add($title, $url = '', $active = false)
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url,
            'active' => $active,
        ];

        return $this;
    }

    /**
     * Add multiple breadcrumb items.
     */
    public function addMultiple($items)
    {
        foreach ($items as $item) {
            $title = $item['title'] ?? '';
            $url = $item['url'] ?? '';
            $active = $item['active'] ?? false;
            $this->add($title, $url, $active);
        }

        return $this;
    }

    /**
     * Clear all breadcrumbs.
     */
    public function clear()
    {
        $this->breadcrumbs = [];

        return $this;
    }

    /**
     * Remove last breadcrumb.
     */
    public function removeLast()
    {
        if (!empty($this->breadcrumbs)) {
            array_pop($this->breadcrumbs);
        }

        return $this;
    }

    /**
     * Auto-generate breadcrumbs from URI.
     */
    public function autoGenerate($customTitles = [])
    {
        $segments = $this->CI->uri->segment_array();

        if (empty($segments)) {
            return $this;
        }

        $url = base_url();

        foreach ($segments as $index => $segment) {
            $url .= $segment . '/';

            // Get custom title or format segment
            if (isset($customTitles[$index])) {
                $title = $customTitles[$index];
            } elseif (isset($customTitles[$segment])) {
                $title = $customTitles[$segment];
            } else {
                $title = $this->formatSegmentTitle($segment);
            }

            // Last segment is active
            $active = ($index === count($segments));

            $this->add($title, $url, $active);
        }

        return $this;
    }

    /**
     * Generate breadcrumbs for modules.
     */
    public function forModule($moduleName, $action = '', $id = '')
    {
        // Module titles mapping
        $moduleTitles = [
            'users' => 'Kullanıcılar',
            'user_roles' => 'Kullanıcı Rolleri',
            'girdikontrol' => 'Girdi Kontrol',
            'proseskontrol' => 'Proses Kontrol',
            'finalkontrol' => 'Final Kontrol',
            'tedarikciler' => 'Tedarikçiler',
            'malzemeler' => 'Malzemeler',
            'raporlar' => 'Raporlar',
            'ayarlar' => 'Ayarlar',
        ];

        $actionTitles = [
            'index' => 'Liste',
            'list' => 'Liste',
            'add' => 'Yeni Ekle',
            'edit' => 'Düzenle',
            'view' => 'Görüntüle',
            'delete' => 'Sil',
        ];

        // Add module
        $moduleTitle = $moduleTitles[$moduleName] ?? $this->formatSegmentTitle($moduleName);
        $this->add($moduleTitle, base_url($moduleName));

        // Add action if exists
        if (!empty($action) && $action !== 'index') {
            $actionTitle = $actionTitles[$action] ?? $this->formatSegmentTitle($action);

            if (!empty($id)) {
                $actionTitle .= ' #' . $id;
            }

            $this->add($actionTitle, '', true);
        }

        return $this;
    }

    /**
     * Generate breadcrumbs for reports.
     */
    public function forReport($reportType, $filters = [])
    {
        $this->add('Raporlar', base_url('raporlar'));

        $reportTitles = [
            'girdi' => 'Girdi Kontrol Raporu',
            'proses' => 'Proses Kontrol Raporu',
            'final' => 'Final Kontrol Raporu',
            'tedarikci' => 'Tedarikçi Raporu',
            'malzeme' => 'Malzeme Raporu',
            'ozet' => 'Özet Rapor',
        ];

        $reportTitle = $reportTitles[$reportType] ?? $this->formatSegmentTitle($reportType);

        // Add filter info to title if exists
        if (!empty($filters)) {
            $filterInfo = [];
            if (!empty($filters['date_start'])) {
                $filterInfo[] = $filters['date_start'];
            }
            if (!empty($filters['date_end'])) {
                $filterInfo[] = $filters['date_end'];
            }
            if (!empty($filterInfo)) {
                $reportTitle .= ' (' . implode(' - ', $filterInfo) . ')';
            }
        }

        $this->add($reportTitle, '', true);

        return $this;
    }

    /**
     * Render breadcrumbs HTML.
     */
    public function render($includeHome = true, $wrapperClass = 'breadcrumb')
    {
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="' . $wrapperClass . '">';

        // Add home if requested
        if ($includeHome) {
            $homeClass = empty($this->breadcrumbs) ? 'breadcrumb-item active' : 'breadcrumb-item';
            $html .= '<li class="' . $homeClass . '">';

            if (empty($this->breadcrumbs)) {
                $html .= '<span>' . safe_output($this->homeTitle) . '</span>';
            } else {
                $html .= '<a href="' . $this->homeUrl . '">' . safe_output($this->homeTitle) . '</a>';
            }

            $html .= '</li>';
        }

        // Add breadcrumb items
        $totalItems = count($this->breadcrumbs);
        foreach ($this->breadcrumbs as $index => $item) {
            $isLast = ($index === $totalItems - 1);
            $isActive = $item['active'] || $isLast;

            $class = $isActive ? 'breadcrumb-item active' : 'breadcrumb-item';
            $html .= '<li class="' . $class . '">';

            if ($isActive || empty($item['url'])) {
                $html .= '<span>' . safe_output($item['title']) . '</span>';
            } else {
                $html .= '<a href="' . $item['url'] . '">' . safe_output($item['title']) . '</a>';
            }

            $html .= '</li>';
        }

        $html .= '</ol>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * Render simple breadcrumbs (without Bootstrap classes).
     */
    public function renderSimple($includeHome = true, $wrapperClass = 'breadcrumb-simple')
    {
        $items = [];

        // Add home
        if ($includeHome) {
            if (empty($this->breadcrumbs)) {
                $items[] = '<span class="current">' . safe_output($this->homeTitle) . '</span>';
            } else {
                $items[] = '<a href="' . $this->homeUrl . '">' . safe_output($this->homeTitle) . '</a>';
            }
        }

        // Add breadcrumb items
        $totalItems = count($this->breadcrumbs);
        foreach ($this->breadcrumbs as $index => $item) {
            $isLast = ($index === $totalItems - 1);
            $isActive = $item['active'] || $isLast;

            if ($isActive || empty($item['url'])) {
                $items[] = '<span class="current">' . safe_output($item['title']) . '</span>';
            } else {
                $items[] = '<a href="' . $item['url'] . '">' . safe_output($item['title']) . '</a>';
            }
        }

        $html = '<div class="' . $wrapperClass . '">';
        $html .= implode(' ' . $this->separator . ' ', $items);
        $html .= '</div>';

        return $html;
    }

    /**
     * Get breadcrumbs as array.
     */
    public function toArray($includeHome = true)
    {
        $items = [];

        if ($includeHome) {
            $items[] = [
                'title' => $this->homeTitle,
                'url' => $this->homeUrl,
                'active' => empty($this->breadcrumbs),
            ];
        }

        foreach ($this->breadcrumbs as $item) {
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Get breadcrumbs count.
     */
    public function count()
    {
        return count($this->breadcrumbs);
    }

    /**
     * Format segment title.
     */
    private function formatSegmentTitle($segment)
    {
        // Remove numbers and special characters
        $title = preg_replace('/[^a-zA-Z\s]/', ' ', $segment);

        // Convert to title case
        $title = ucwords(str_replace(['_', '-'], ' ', $title));

        // Turkish character replacements
        $replacements = [
            'Girdi' => 'Girdi',
            'Proses' => 'Proses',
            'Final' => 'Final',
            'Kontrol' => 'Kontrol',
            'Tedarikci' => 'Tedarikçi',
            'Malzeme' => 'Malzeme',
            'Rapor' => 'Rapor',
            'Kullanici' => 'Kullanıcı',
            'Ayar' => 'Ayar',
        ];

        foreach ($replacements as $search => $replace) {
            $title = str_ireplace($search, $replace, $title);
        }

        return trim($title);
    }

    /**
     * Magic method to render breadcrumbs.
     */
    public function __toString()
    {
        return $this->render();
    }
}
