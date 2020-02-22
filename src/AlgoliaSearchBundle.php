<?php

namespace Zain\LaravelDoctrine\Algolia;

use Algolia\AlgoliaSearch\Support\UserAgent;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel as SfKernel;

final class AlgoliaSearchBundle extends Bundle
{
    /**
     * Holds the bundle version.
     */
    const VERSION = '4.1.0';

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();

        UserAgent::addCustomUserAgent('Symfony Search Bundle', self::VERSION);
        UserAgent::addCustomUserAgent('Symfony', SfKernel::VERSION);
    }
}
