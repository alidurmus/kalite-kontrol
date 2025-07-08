<?php
/**
 * Automatic XSS Fix Script for QMS
 * 
 * Bu script XSS açıklarını otomatik olarak düzeltir
 * ÖNEMLİ: Production'da çalıştırmadan önce backup alın!
 */

echo "=== QMS XSS Auto-Fix Script ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$fixed_files = 0;
$total_fixes = 0;
$dry_run = true; // Güvenlik için önce dry-run

// Scan directories
$scan_dirs = [
    'application/views',
    'application/modules'
];

if (isset($argv[1]) && $argv[1] === '--fix') {
    $dry_run = false;
    echo "🔧 FIXING MODE: Changes will be applied!\n\n";
} else {
    echo "🔍 DRY RUN MODE: No changes will be made.\n";
    echo "Use --fix parameter to apply changes.\n\n";
}

foreach ($scan_dirs as $dir) {
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $filepath = $file->getPathname();
                $content = file_get_contents($filepath);
                $original_content = $content;
                $file_fixes = 0;
                
                // Fix 1: Replace unsafe echo statements
                $content = preg_replace_callback(
                    '/echo\s+\$([a-zA-Z_][a-zA-Z0-9_]*(?:->[a-zA-Z_][a-zA-Z0-9_]*)*);/',
                    function($matches) {
                        $var = '$' . $matches[1];
                        // Skip already safe functions
                        if (strpos($var, 'form_error') !== false || 
                            strpos($var, 'base_url') !== false ||
                            strpos($var, 'safe_output') !== false) {
                            return $matches[0];
                        }
                        return "echo safe_output($var);";
                    },
                    $content,
                    -1,
                    $count1
                );
                $file_fixes += $count1;
                
                // Fix 2: Replace unsafe form input values
                $content = preg_replace_callback(
                    '/value="([^"]*\$[^"]*)"/',
                    function($matches) {
                        $value = $matches[1];
                        // Skip already safe functions
                        if (strpos($value, 'safe_attr') !== false || 
                            strpos($value, 'set_value') !== false ||
                            strpos($value, 'htmlspecialchars') !== false) {
                            return $matches[0];
                        }
                        
                        // Extract PHP variable
                        if (preg_match('/\$([a-zA-Z_][a-zA-Z0-9_]*(?:->[a-zA-Z_][a-zA-Z0-9_]*)*)/', $value, $var_matches)) {
                            $var = '$' . $var_matches[1];
                            return 'value="<?php echo safe_attr(' . $var . '); ?>"';
                        }
                        return $matches[0];
                    },
                    $content,
                    -1,
                    $count2
                );
                $file_fixes += $count2;
                
                // Fix 3: Replace unsafe href attributes
                $content = preg_replace_callback(
                    '/href="([^"]*\$[^"]*)"/',
                    function($matches) {
                        $url = $matches[1];
                        // Skip already safe functions
                        if (strpos($url, 'safe_url') !== false || 
                            strpos($url, 'base_url') !== false) {
                            return $matches[0];
                        }
                        
                        // Extract PHP variable
                        if (preg_match('/\$([a-zA-Z_][a-zA-Z0-9_]*(?:->[a-zA-Z_][a-zA-Z0-9_]*)*)/', $url, $var_matches)) {
                            $var = '$' . $var_matches[1];
                            return 'href="<?php echo safe_url(' . $var . '); ?>"';
                        }
                        return $matches[0];
                    },
                    $content,
                    -1,
                    $count3
                );
                $file_fixes += $count3;
                
                // Fix 4: Add security helper load
                if ($file_fixes > 0 && strpos($content, '$this->load->helper(\'security\')') === false) {
                    // Add helper load after opening PHP tag
                    $content = preg_replace(
                        '/(<\?php\s*)/i',
                        '$1' . "\n// Load security helper for XSS protection\n" . 
                        '$this->load->helper(\'security\');' . "\n",
                        $content,
                        1
                    );
                }
                
                if ($content !== $original_content) {
                    $fixed_files++;
                    $total_fixes += $file_fixes;
                    
                    echo "Fixed: $filepath ($file_fixes issues)\n";
                    
                    if (!$dry_run) {
                        // Backup original file
                        $backup_file = $filepath . '.backup.' . date('Ymd_His');
                        copy($filepath, $backup_file);
                        
                        // Write fixed content
                        file_put_contents($filepath, $content);
                        echo "  ✅ Backup created: $backup_file\n";
                    }
                }
            }
        }
    }
}

echo "\n=== Summary ===\n";
echo "Files processed: $fixed_files\n";
echo "Total fixes applied: $total_fixes\n";

if ($dry_run) {
    echo "\n⚠️  This was a DRY RUN. No files were modified.\n";
    echo "Run with --fix parameter to apply changes.\n";
} else {
    echo "\n✅ All fixes applied successfully!\n";
    echo "Backup files created with .backup.YYYYMMDD_HHMMSS extension\n";
}

echo "\n=== Next Steps ===\n";
echo "1. Test the application thoroughly\n";
echo "2. Run security audit again to verify fixes\n";
echo "3. Remove backup files after testing\n";
echo "4. Update autoload.php to include security helper\n"; 