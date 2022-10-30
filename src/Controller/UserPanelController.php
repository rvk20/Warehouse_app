<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelController extends AbstractController
{
    #[Route('/panel', name: 'app_user_panel')]
    public function index(): Response
    {
        return $this->render('user_panel/index.html.twig', [
            'controller_name' => 'UserPanelController',
        ]);
    }
}
