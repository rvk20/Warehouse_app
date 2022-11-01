<?php

namespace App\Controller;

use App\Form\ReceiptProductForm;
use App\Service\WarehouseService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelController extends AbstractController
{
    private $warehouseService;

    public function __construct(ManagerRegistry $doctrine, WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    #[Route('/panel', name: 'app_user_panel')]
    public function index(Request $request): Response
    {
        $text = "abc";
        $form = $this->createForm(ReceiptProductForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $data = $form->getData();
            $text = $data['product']->getId();
            $files = $form['file']->getData();
 
            if ($files) 
            {
                foreach ($files as $file) 
                {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $file->getClientOriginalName()
                    );
                }
            }
        }

        return $this->render('user_panel/index.html.twig', [
            'warehouses' => $this->warehouseService->showWarehouses($this->getUser()->getId(), $this->getUser()->getRoles()[0]),
            'form' => $form->createView()
        ]);
    }

    #[Route('/panel/{id}', name: 'app_warehouse')]
    public function warehouse(int $id, Request $request): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');

        return $this->render('user_panel/warehouse.html.twig', [
            'controller_name' => 'abc'
        ]);
    }
}
