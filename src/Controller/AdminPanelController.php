<?php

namespace App\Controller;

use App\Form\UserForm;
use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    #[Route('/admin', name: 'app_admin_panel')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UserForm::class);

        echo $this->adminService->addUser($form, $request);

        return $this->render('admin_panel/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
