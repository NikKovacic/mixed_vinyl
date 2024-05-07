<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
    #[Route('/', name: 'app_vinyl_homepage')]
    public function homepage(): Response
    {
        return new Response('Title: PB and Jams');
    }

    #[Route('/browse/{slug}', name: 'app_vinyl_browse')]
    public function browse(?string $slug = null): Response
    {
        $title = 'All Genres';
        if ($slug) {
            $title = u(str_replace('-', ' ', $slug))->title();
        }

        return new Response($title);
    }
}
