<?php
/**
 * Breadcrumb Navigation View
 * 
 * Usage: $this->load->view('includes/breadcrumb', $breadcrumb_data);
 * Or just: $this->load->view('includes/breadcrumb'); for auto-generation
 */

// Load breadcrumb library if not already loaded
if (!isset($this->breadcrumb)) {
    $this->load->library('breadcrumb');
}

// Check if breadcrumb data is provided
if (isset($breadcrumb_data) && is_array($breadcrumb_data)) {
    // Manual breadcrumb setup
    $this->breadcrumb->clear();
    
    if (isset($breadcrumb_data['home'])) {
        $this->breadcrumb->setHome($breadcrumb_data['home']['title'], $breadcrumb_data['home']['url'] ?? '');
    }
    
    if (isset($breadcrumb_data['items'])) {
        $this->breadcrumb->addMultiple($breadcrumb_data['items']);
    }
} else {
    // Auto-generate breadcrumbs based on current URI
    $segment1 = $this->uri->segment(1);
    $segment2 = $this->uri->segment(2);
    $segment3 = $this->uri->segment(3);
    
    // Clear existing breadcrumbs
    $this->breadcrumb->clear();
    
    // Generate module-specific breadcrumbs
    if ($segment1) {
        switch ($segment1) {
            case 'girdikontrol':
                $this->breadcrumb->forModule('girdikontrol', $segment2, $segment3);
                break;
                
            case 'proseskontrol':
                $this->breadcrumb->forModule('proseskontrol', $segment2, $segment3);
                break;
                
            case 'finalkontrol':
                $this->breadcrumb->forModule('finalkontrol', $segment2, $segment3);
                break;
                
            case 'tedarikciler':
                $this->breadcrumb->forModule('tedarikciler', $segment2, $segment3);
                break;
                
            case 'malzemeler':
                $this->breadcrumb->forModule('malzemeler', $segment2, $segment3);
                break;
                
            case 'users':
                $this->breadcrumb->forModule('users', $segment2, $segment3);
                break;
                
            case 'user_roles':
                $this->breadcrumb->forModule('user_roles', $segment2, $segment3);
                break;
                
            case 'raporlar':
                $this->breadcrumb->forReport($segment2, $_GET ?? []);
                break;
                
            default:
                // Generic auto-generation
                $this->breadcrumb->autoGenerate();
                break;
        }
    }
}

// Render breadcrumb if there are items
if ($this->breadcrumb->count() > 0): ?>
<div class="breadcrumb-container">
    <?php echo $this->breadcrumb->render(true, 'breadcrumb'); ?>
</div>
<?php endif; ?> 