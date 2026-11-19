<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public page cache
    |--------------------------------------------------------------------------
    |
    | Caches the heavy read queries that back the public portfolio pages.
    | Keys are scoped by locale (en/id) so translations never leak between
    | languages. All keys are invalidated automatically by PortfolioCacheObserver
    | whenever a content model is saved or deleted in Filament.
    |
    | Disabled by default in tests (see phpunit.xml) so existing tests are
    | deterministic; cache tests opt in explicitly with config([...]).
    |
    */
    'enabled' => env('PORTFOLIO_CACHE_ENABLED', true),

    'ttl' => env('PORTFOLIO_CACHE_TTL', 3600),
];
