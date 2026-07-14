<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Footer Attribution
    |--------------------------------------------------------------------------
    |
    | Shown at the bottom of every public page. Each instance can brand it
    | via env, e.g. "powered by alvarocdev.com" pointing to your site.
    |
    */

    'attribution' => [
        'label' => env('NEXO_ATTRIBUTION_LABEL', 'made with Nexo'),
        'url' => env('NEXO_ATTRIBUTION_URL', 'https://github.com/alvarocdev-git/nexo-links'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reserved Usernames
    |--------------------------------------------------------------------------
    |
    | Usernames that can never be registered because they collide with app
    | routes or could be used to impersonate the site itself.
    |
    */

    'reserved_usernames' => [
        'about',
        'admin',
        'administrator',
        'api',
        'app',
        'assets',
        'blog',
        'build',
        'contact',
        'dashboard',
        'demo',
        'docs',
        'email',
        'help',
        'home',
        'l',
        'login',
        'logout',
        'mail',
        'nexo',
        'official',
        'password',
        'privacy',
        'profile',
        'register',
        'root',
        'settings',
        'setup',
        'storage',
        'support',
        'system',
        'terms',
        'up',
        'verify',
        'www',
    ],

];
