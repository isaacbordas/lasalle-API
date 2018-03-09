<?php

namespace App\Bundle\FilmBundle\Film\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultFilmController extends Controller
{
    public function changeLangAction(string $lang, string $route, $id = null): RedirectResponse
    {
        $id = (empty($id) ? null : $id . '/');
        $this->get('session')->set('_locale', $lang);

        return new RedirectResponse('/' . $lang . '/' . $route . '/' . $id);
    }
}
