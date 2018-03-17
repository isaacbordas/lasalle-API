<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClearCacheController extends Controller
{
        public function execute(Request $request): RedirectResponse
        {
            $cache = $this->get('CacheService');
            $cache->cacheClear();
            $referer = $request->headers->get('referer');

            return new RedirectResponse($referer);
        }
}