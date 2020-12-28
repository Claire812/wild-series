<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MySpaceController extends AbstractController
{
    /**
     * @Route("/my-profile", name="my_space")
     */
    public function index(): Response
    {
        return $this->render('my_space/index.html.twig', [
            'controller_name' => 'MySpaceController',
        ]);
    }
}
