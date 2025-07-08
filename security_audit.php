<?php
/**
 * Security Audit Script for QMS
 * 
 * Bu script projedeki güvenlik açıklarını tarar ve rapor eder
 */

echo "=== QMS Security Audit Report ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$security_issues = [];
$files_scanned = 0;
$total_issues = 0;

// Scan directories
$scan_dirs = [
    'application/views',
    'application/modules'
];

foreach ($scan_dirs as $dir) {
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $files_scanned++;
                $content = file_get_contents($file->getPathname());
                $issues = scanFileForSecurityIssues($file->getPathname(), $content);
                
                if (!empty($issues)) {
                    $security_issues[$file->getPathname()] = $issues;
                    $total_issues += count($issues);
                }
            }
        }
    }
}

// Report results
echo "Files scanned: $files_scanned\n";
echo "Files with issues: " . count($security_issues) . "\n";
echo "Total issues found: $total_issues\n\n";

if (!empty($security_issues)) {
    echo "=== Security Issues Found ===\n\n";
    
    foreach ($security_issues as $file => $issues) {
        echo "File: $file\n";
        foreach ($issues as $issue) {
            echo "  Line {$issue['line']}: {$issue['type']} - {$issue['description']}\n";
        }
        echo "\n";
    }
} else {
    echo "✅ No security issues found!\n";
}

// Summary by issue type
$issue_summary = [];
foreach ($security_issues as $file => $issues) {
    foreach ($issues as $issue) {
        $type = $issue['type'];
        if (!isset($issue_summary[$type])) {
            $issue_summary[$type] = 0;
        }
        $issue_summary[$type]++;
    }
}

if (!empty($issue_summary)) {
    echo "=== Issue Summary ===\n";
    foreach ($issue_summary as $type => $count) {
        echo "$type: $count issues\n";
    }
}

echo "\n=== Recommendations ===\n";
echo "1. Replace all 'echo \$variable' with 'echo safe_output(\$variable)'\n";
echo "2. Use safe_attr() for HTML attributes\n";
echo "3. Use safe_url() for URLs\n";
echo "4. Add input validation for all user inputs\n";
echo "5. Enable CSRF protection (already enabled)\n";

function scanFileForSecurityIssues($filepath, $content) {
    $issues = [];
    $lines = explode("\n", $content);
    
    foreach ($lines as $lineNum => $line) {
        $lineNum++; // 1-indexed
        
        // Check for unsafe echo statements
        if (preg_match('/echo\s+\$[^;]+;/', $line) && 
            !preg_match('/safe_output|htmlspecialchars|form_error|base_url/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'XSS_RISK',
                'description' => 'Unescaped output: ' . trim($line)
            ];
        }
        
        // Check for unsafe form inputs
        if (preg_match('/value="[^"]*\$[^"]*"/', $line) && 
            !preg_match('/safe_output|safe_attr|htmlspecialchars/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'XSS_FORM_INPUT',
                'description' => 'Unescaped form input value: ' . trim($line)
            ];
        }
        
        // Check for unsafe href attributes
        if (preg_match('/href="[^"]*\$[^"]*"/', $line) && 
            !preg_match('/safe_url|base_url/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'XSS_URL',
                'description' => 'Unescaped URL: ' . trim($line)
            ];
        }
        
        // Check for SQL injection risks (raw queries)
        if (preg_match('/\$this->db->query\s*\(\s*["\'][^"\']*\$/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'SQL_INJECTION',
                'description' => 'Potential SQL injection: ' . trim($line)
            ];
        }
        
        // Check for file inclusion vulnerabilities
        if (preg_match('/(include|require)(_once)?\s*\(\s*\$/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'FILE_INCLUSION',
                'description' => 'Dynamic file inclusion: ' . trim($line)
            ];
        }
        
        // Check for eval() usage
        if (preg_match('/eval\s*\(/', $line)) {
            $issues[] = [
                'line' => $lineNum,
                'type' => 'CODE_INJECTION',
                'description' => 'eval() usage detected: ' . trim($line)
            ];
        }
    }
    
    return $issues;
} 