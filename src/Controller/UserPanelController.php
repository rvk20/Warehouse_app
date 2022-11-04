<?php

namespace App\Controller;

use App\Form\ReceiptProductForm;
use App\Form\ReleaseProductForm;
use App\Service\WarehouseService;
use App\Service\ReceiptProductService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelController extends AbstractController
{
    private $warehouseService;
    private $receiptProductService;

    public function __construct(ManagerRegistry $doctrine, WarehouseService $warehouseService, ReceiptProductService $receiptProductService)
    {
        $this->warehouseService = $warehouseService;
        $this->receiptProductService = $receiptProductService;
    }

    #[Route('/panel', name: 'app_user_panel')]
    public function index(): Response
    {
        return $this->render('user_panel/index.html.twig', [
            'warehouses' => $this->warehouseService->showWarehouses($this->getUser()->getId(), $this->getUser()->getRoles()[0])
        ]);
    }

    #[Route('/panel/{id}/', name: 'app_warehouse')]
    public function warehouse(int $id): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');

        return $this->render('user_panel/warehouse.html.twig', [         
            'state' => $this->receiptProductService->showState($this->warehouseService->showWarehouse($id))
        ]);
    }

    #[Route('/panel/{id}/add', name: 'app_warehouse_add')]
    public function warehouseAdd(int $id, Request $request): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');

        $form = $this->createForm(ReceiptProductForm::class);

        $this->receiptProductService->receiptProduct($form, $request, $this->getParameter('file_directory'), $this->warehouseService->showWarehouse($id));

        return $this->render('user_panel/warehouseadd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/panel/{id}/history', name: 'app_warehouse_history')]
    public function warehouseHistory(int $id): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');

        return $this->render('user_panel/warehousehistory.html.twig', [         
            'properties' => $this->receiptProductService->showProperties($this->warehouseService->showWarehouse($id))
        ]);
    }

    #[Route('/panel/{id}/history/{pid}', name: 'app_warehouse_history_details')]
    public function warehouseHistoryDetails(int $id, int $pid): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');
        
        $product = $this->receiptProductService->showPropertiesDetails($pid);

        return $this->render('user_panel/warehousehistorydetails.html.twig', [  
            'product' => $product,       
            'files' => $this->receiptProductService->showFiles($product)
        ]);
    }

    #[Route('/panel/{id}/release', name: 'app_warehouse_release')]
    public function warehouseRelease(int $id, Request $request): Response
    {
        if(!$this->warehouseService->checkAccess($this->getUser()->getId(), $id, $this->getUser()->getRoles()[0]))
            return $this->redirectToRoute('app_user_panel');
        
        $form = $this->createForm(ReleaseProductForm::class);

        $this->receiptProductService->releaseProduct($form, $request, $this->warehouseService->showWarehouse($id));

        return $this->render('user_panel/warehouserelease.html.twig', [  
            'form' => $form->createView()       
        ]);
    }
}
