<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'auth'     => \App\Filters\AuthFilter::class,
    ];

    public $globals = [
        'before' => [
            'csrf' => ['except' => ['auth/login', 'auth/register']]
        ],
        'after' => [
            'toolbar'
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => [
            'before' => ['home/*', 'admin/*']
        ]
    ];
}