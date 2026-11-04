<?php

declare(strict_types=1);

namespace Francken\Shared;

use Illuminate\Foundation\Vite as LaravelVite;

/**
 * Loads the stylesheets that Vite builds (see vite.config.js) with relative urls, like
 * the images and uploads, so that they also load on a phone during development, and
 * reads the address of the development server from public/vite.hot instead of
 * public/hot, which mix() would use for the stylesheets of the admin
 */
final class Vite extends LaravelVite
{
    public function hotFile()
    {
        return $this->hotFile ?? public_path('vite.hot');
    }

    protected function assetPath($path, $secure = null)
    {
        return '/' . ltrim($path, '/');
    }
}
