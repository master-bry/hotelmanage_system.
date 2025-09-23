<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use App\Filters\Auth;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'auth' => Auth::class,
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['auth/login', 'auth/register']],
            // 'auth' => ['except' => ['auth/*', '/']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth:user' => ['before' => ['home/*']],
        'auth:staff' => ['before' => ['admin/*']],
    ];
}