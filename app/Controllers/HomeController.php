<?php

namespace App\Controllers;

class HomeController extends Controller
{
	public function index($request, $response, $args)
	{
		return $this->c->view->render($response, 'home.twig');
	}
}