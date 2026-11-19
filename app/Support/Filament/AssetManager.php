<?php

namespace App\Support\Filament;

use Filament\Support\Assets\AssetManager as BaseAssetManager;
use Filament\Support\Assets\Js;

/**
 * Drops the two syntax-highlighter bundles published by awcodes/richer-editor.
 *
 * Neither `code-block-shiki` (~9.65 MB) nor `code-block-lowlight` (~426 KB) is
 * requested by any plugin configured in this app (PostForm uses only Embed,
 * SourceCode, FullScreen, Id, Link and Emoji plugins). They are registered as
 * `loadedOnRequest()`, so they are dead weight on disk and in the image.
 *
 * Filtering them out of getScripts() prevents both:
 *   - runtime registration (nothing can request a missing script), and
 *   - `filament:assets` / `filament:upgrade` from republishing them.
 */
class AssetManager extends BaseAssetManager
{
    /** @var list<string> */
    private const DISABLED_SCRIPTS = [
        'richer-editor/code-block-shiki',
        'richer-editor/code-block-lowlight',
    ];

    /**
     * @param  array<string> | null  $packages
     * @return array<Js>
     */
    public function getScripts(?array $packages = null, bool $withCore = true): array
    {
        return array_values(array_filter(
            parent::getScripts($packages, $withCore),
            fn (Js $script): bool => ! in_array($script->getId(), self::DISABLED_SCRIPTS, true),
        ));
    }
}
