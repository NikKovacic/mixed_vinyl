<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    private HttpClientInterface $githubContentClient;
    private CacheInterface $cache;
    private bool $isDebug;

    public function __construct(HttpClientInterface $githubContentClient, CacheInterface $cache, #[Autowire('%kernel.debug%')] bool $isDebug)
    {
        // Only githubContentClient is used here!
        $this->githubContentClient = $githubContentClient;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
    }

    public function findAll()
    {
        return $this->cache->get('mixes_data', function (CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });
    }
}
