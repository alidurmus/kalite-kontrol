<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

// Safe output function for error pages (direct implementation)
if (!function_exists('safe_output')) {
    function safe_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
?>

A PHP Error was encountered

Severity:    <?php echo safe_output($severity), "\n"; ?>
Message:     <?php echo safe_output($message), "\n"; ?>
Filename:    <?php echo safe_output($filepath), "\n"; ?>
Line Number: <?php echo safe_output($line); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Backtrace:
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	File: <?php echo safe_output($error['file']), "\n"; ?>
	Line: <?php echo safe_output($error['line']), "\n"; ?>
	Function: <?php echo safe_output($error['function']), "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
