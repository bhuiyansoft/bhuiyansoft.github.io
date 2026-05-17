<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'auth'     => \App\Filters\AuthFilter::class,
        'guest'    => \App\Filters\GuestFilter::class,
        'tenant'   => \App\Filters\TenantFilter::class,
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*', 's/*']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}
