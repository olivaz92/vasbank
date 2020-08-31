<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChoixformuleController extends AbstractController
{
    /**
     * @Route("/choixformule", name="choixformule")
     */
    public function index()
    {
        return $this->render('choixformule/index.html.twig', [
            'controller_name' => 'ChoixformuleController',
        ]);
    }
}
