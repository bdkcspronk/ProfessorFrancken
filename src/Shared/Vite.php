<?php

declare(strict_types=1);

namespace Francken\Shared;

use Illuminate\Foundation\Vite as LaravelVite;

/**
 * Loads the files that Vite builds (see vite.config.js) with relative urls, like the images
 * and uploads, so that they also load on a phone during development
 */
final class Vite extends LaravelVite
{
    protected function assetPath($path, $secure = null)
    {
        return '/' . ltrim($path, '/');
    }
}
