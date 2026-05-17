<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost/hrm/public/';
    public string $appTimezone = 'Asia/Dhaka';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $CSPEnabled = '';
    public string $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';
    public string $sessionCookieName = 'hrm_session';
    public int $sessionExpiration = 7200;
    public string $sessionSavePath = WRITEPATH . 'session';
    public bool $sessionMatchIP = false;
    public int $sessionTimeToUpdate = 300;
    public bool $sessionRegenerateDestroy = false;
    public string $cookiePrefix = 'hrm_';
    public string $cookieDomain = '';
    public string $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieHTTPOnly = true;
    public ?string $cookieSameSite = 'Lax';
    public string $proxyIPs = '';
    public string $CSRFTokenName = 'csrf_token';
    public string $CSRFHeaderName = 'X-CSRF-TOKEN';
    public string $CSRFCookieName = 'csrf_cookie';
    public int $CSRFExpire = 7200;
    public bool $CSRFRegenerate = true;
    public bool $CSRFRedirect = true;
    public string $CSRFSameSite = 'Lax';
    public bool $CSRFProtection = true;
}
