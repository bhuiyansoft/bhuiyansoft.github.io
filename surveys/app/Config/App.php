<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost:8080/';
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'UTC';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public string $proxyIPs = '';
    public string $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';
    public string $sessionCookieName = 'surveys_session';
    public int $sessionExpiration = 7200;
    public string $sessionSavePath = WRITEPATH . 'session';
    public bool $sessionMatchIP = false;
    public int $sessionTimeToUpdate = 300;
    public bool $sessionRegenerateDestroy = false;
    public string $cookiePrefix = '';
    public string $cookieDomain = '';
    public string $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieHTTPOnly = true;
    public ?string $cookieSameSite = 'Lax';
    public bool $CSRFProtection = true;
    public string $CSRFTokenName = 'csrf_token';
    public string $CSRFHeaderName = 'X-CSRF-TOKEN';
    public string $CSRFCookieName = 'csrf_cookie';
    public int $CSRFExpire = 7200;
    public bool $CSRFRegenerate = false;
    public bool $CSRFRedirect = false;
    public string $CSRFSameSite = 'Lax';
    public bool $sortURLParams = false;
    public int $responseCompression = 0;

    // Application-specific settings
    public string $appName = 'SurveyStack';
    public string $appTagline = 'Powerful SaaS Survey Platform';
    public string $supportEmail = 'support@surveystack.io';
    public string $encryptionKey = '';
}
