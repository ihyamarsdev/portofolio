<?php

return [
    /**
     * Laravel AI provider name for CV analysis.
     */
    'provider' => env('CV_AI_PROVIDER', 'cv'),

    /**
     * AI model name for CV analysis.
     */
    'model' => env('CV_MODEL', 'cv'),
];
