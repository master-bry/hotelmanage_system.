<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\Auth;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'auth'     => Auth::class,
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => [
                'home/*',
                'admin/*',
            ],
            'after' => []
        ]
    ];
}