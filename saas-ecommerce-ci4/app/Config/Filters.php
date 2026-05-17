<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'       => \CodeIgniter\Filters\CSRF::class,
        'toolbar'    => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'   => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars' => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
        'auth'       => \App\Filters\AuthFilter::class,
        'tenant'     => \App\Filters\TenantFilter::class,
        'cors'       => \App\Filters\CorsFilter::class,
    ];

    public array $globals = [
        'before' => [
            'honeypot',
            // 'csrf',
        ],
        'after' => [
            'toolbar',
            'honeypot',
        ],
    ];

    public array $methods = [];
    public array $filters = [];
}
