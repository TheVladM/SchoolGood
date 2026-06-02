<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    */
    'show_warnings' => false, // Emit warnings while generating PDF
    'convert_html5_entities' => false, // Convert HTML5 entities to Latin characters
    'auto_script_to_url' => true, // Convert script tags to URLs
    'enable_php' => false, // Enable PHP processing in HTML
    'enable_javascript' => false, // Enable JavaScript processing in HTML
    'enable_remote' => false, // Enable remote file access
    'enable_css_float' => true, // Enable CSS float property
    'enable_html5_parser' => true, // Enable HTML5 parser
    'font_subsetting' => true, // Enable font subsetting
    'pdf_backend' => 'CPDF', // PDF backend: 'CPDF', 'DOMPDF', 'GD'
    'progress_bar' => false, // Show progress bar

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    */
    'default_font' => 'sans-serif',
    'default_paper_size' => 'a4',
    'default_paper_orientation' => 'portrait',
    'default_font' => 'serif',

    /*
    |--------------------------------------------------------------------------
    | Custom Fonts
    |--------------------------------------------------------------------------
    |
    */
    'custom_fonts_dir' => [],
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),

    /*
    |--------------------------------------------------------------------------
    | Temp Directory
    |--------------------------------------------------------------------------
    |
    */
    'temp_dir' => sys_get_temp_dir(),

    /*
    |--------------------------------------------------------------------------
    | Options for specific backends
    |--------------------------------------------------------------------------
    |
    */
    'options' => [
        'font' => [
            'serif' => [
                'family' => 'Times New Roman',
                'weight' => 'normal',
                'style' => 'normal',
            ],
            'sans-serif' => [
                'family' => 'Arial',
                'weight' => 'normal',
                'style' => 'normal',
            ],
        ],
    ],
];
