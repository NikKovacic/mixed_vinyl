<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    private HttpClientInterface $githubContentClient;
    private CacheInterface $cache;
    private bool $isDebug;

    // private DebugCommand $twigDebugCommand;

    public function __construct(
        HttpClientInterface $githubContentClient,
        CacheInterface $cache,
        #[Autowire('%kernel.debug%')] bool $isDebug,
        // #[Autowire(service: 'twig.command.debug')] DebugCommand $twigDebugCommand,
    ) {
        // Only githubContentClient is used here!
        $this->githubContentClient = $githubContentClient;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        // $this->twigDebugCommand = $twigDebugCommand;
    }

    public function findAll()
    {
        //  $output = new BufferedOutput();
        //  $this->twigDebugCommand->run(new ArrayInput([]), $output);
        //  dd($output);

        return $this->cache->get('mixes_data', function (CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });
    }
}
