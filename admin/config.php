<?php
session_start();

$config_file = __DIR__ . '/admin_data.php';

$admin_config = [
    'setup_complete' => false,
    'email' => '',
    'password_hash' => '',
    'root_path' => dirname(__DIR__),
    'site_name' => 'SEO Toolkit Pro',
    'primary_color' => '#0057ff',
    'secondary_color' => '#00a3a3',
    'header_code' => '',
    'footer_code' => ''
];

if (file_exists($config_file)) {
    $config_content = file_get_contents($config_file);
    $config_content = str_replace(array('<?php', '?>', 'return '), '', $config_content);
    $loaded_config = eval('return ' . $config_content . ';');
    if (is_array($loaded_config)) {
        foreach ($loaded_config as $key => $value) {
            $admin_config[$key] = $value;
        }
    }
}

function saveAdminConfig($config) {
    global $config_file;
    $export_config = [];
    $keys = ['setup_complete', 'email', 'password_hash', 'site_name', 'primary_color', 'secondary_color', 'header_code', 'footer_code'];
    foreach ($keys as $key) {
        if (isset($config[$key])) {
            $export_config[$key] = $config[$key];
        }
    }
    $content = "<?php\nreturn " . var_export($export_config, true) . ";\n?>";
    return file_put_contents($config_file, $content);
}

function updateAllFiles($site_name, $primary_color, $secondary_color) {
    $root_path = dirname(__DIR__);
    $updated_files = [];
    
    // Files and folders to update
    $paths = [
        $root_path . '/index-static.html',
        $root_path . '/about.html',
        $root_path . '/contact.html',
        $root_path . '/disclaimer.html',
        $root_path . '/privacy-policy.html',
        $root_path . '/terms.html',
        $root_path . '/seo-analyzer/index.html',
        $root_path . '/keyword-density-checker/index.html',
        $root_path . '/meta-tag-generator/index.html',
        $root_path . '/robots-txt-generator/index.html',
        $root_path . '/schema-markup-generator/index.html',
        $root_path . '/url-extractor/index.html'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Update brand name
            $content = preg_replace('/<span class="brand-name">.*?<\/span>/', '<span class="brand-name">' . htmlspecialchars($site_name) . '</span>', $content);
            
            // Update colors in CSS
            $content = preg_replace('/--primary: #[a-f0-9]{6}/', '--primary: ' . $primary_color, $content);
            $content = preg_replace('/--primary-hover: #[a-f0-9]{6}/', '--primary-hover: ' . $primary_color, $content);
            $content = preg_replace('/--secondary: #[a-f0-9]{6}/', '--secondary: ' . $secondary_color, $content);
            
            file_put_contents($path, $content);
            $updated_files[] = basename(dirname($path)) . '/' . basename($path);
        }
    }
    
    return $updated_files;
}

function injectGlobalCodes($content) {
    global $admin_config;
    if (!empty($admin_config['header_code'])) {
        $content = str_replace('</head>', $admin_config['header_code'] . "\n</head>", $content);
    }
    if (!empty($admin_config['footer_code'])) {
        $content = str_replace('</body>', $admin_config['footer_code'] . "\n</body>", $content);
    }
    return $content;
}
?>