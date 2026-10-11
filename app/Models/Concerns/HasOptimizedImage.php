<?php

namespace App\Models\Concerns;

trait HasOptimizedImage
{
    /**
     * URL of a generated conversion of the first media in a collection.
     * Falls back to the original file when the conversion has not been
     * generated yet (e.g. uploads that predate the conversion), and to an
     * empty string when the collection has no media.
     */
    protected function optimizedMediaUrl(string $collection, string $conversion): string
    {
        $media = $this->getFirstMedia($collection);

        if (! $media) {
            return '';
        }

        return $media->hasGeneratedConversion($conversion)
            ? $media->getUrl($conversion)
            : $media->getUrl();
    }
}
