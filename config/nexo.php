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
    | Social Platforms
    |--------------------------------------------------------------------------
    |
    | Platforms available for the icons footer. "type" drives validation of
    | the stored value: handle, email or phone (E.164 with leading +).
    |
    */

    'social_platforms' => [
        'instagram' => ['label' => 'Instagram', 'url' => 'https://instagram.com/{value}', 'type' => 'handle'],
        'x' => ['label' => 'X', 'url' => 'https://x.com/{value}', 'type' => 'handle'],
        'tiktok' => ['label' => 'TikTok', 'url' => 'https://tiktok.com/@{value}', 'type' => 'handle'],
        'youtube' => ['label' => 'YouTube', 'url' => 'https://youtube.com/@{value}', 'type' => 'handle'],
        'github' => ['label' => 'GitHub', 'url' => 'https://github.com/{value}', 'type' => 'handle'],
        'twitch' => ['label' => 'Twitch', 'url' => 'https://twitch.tv/{value}', 'type' => 'handle'],
        'facebook' => ['label' => 'Facebook', 'url' => 'https://facebook.com/{value}', 'type' => 'handle'],
        'telegram' => ['label' => 'Telegram', 'url' => 'https://t.me/{value}', 'type' => 'handle'],
        'whatsapp' => ['label' => 'WhatsApp', 'url' => 'https://wa.me/{value}', 'type' => 'phone'],
        'email' => ['label' => 'Email', 'url' => 'mailto:{value}', 'type' => 'email'],
        'phone' => ['label' => 'Phone', 'url' => 'tel:{value}', 'type' => 'phone'],
        'website' => ['label' => 'Website', 'url' => '{value}', 'type' => 'url'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Presets
    |--------------------------------------------------------------------------
    |
    | Accent palettes a page owner can pick. "from"/"to" define the gradient
    | used for the avatar ring and highlighted links.
    |
    */

    'themes' => [
        'default' => ['label' => 'Nexo', 'from' => '#6366f1', 'to' => '#d946ef'],
        'ocean' => ['label' => 'Ocean', 'from' => '#0ea5e9', 'to' => '#6366f1'],
        'sunset' => ['label' => 'Sunset', 'from' => '#f97316', 'to' => '#db2777'],
        'forest' => ['label' => 'Forest', 'from' => '#10b981', 'to' => '#0d9488'],
        'mono' => ['label' => 'Mono', 'from' => '#171717', 'to' => '#525252'],
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
