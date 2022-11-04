<?php

namespace App\Controller;

use App\Form\ProductForm;
use App\Form\UserForm;
use App\Form\WarehouseForm;
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
    public function index(): Response
    {
        return $this->render('admin_panel/index.html.twig', [
            
        ]);
    }

    #[Route('/admin/user', name: 'app_show_users')]
    public function showUsers(): Response
    {
        return $this->render('admin_panel/users.html.twig', [
            'users' => $this->adminService->showUsers()
        ]);
    }

    #[Route('/admin/warehouse', name: 'app_show_warehouses')]
    public function showWarehouses(): Response
    {
        return $this->render('admin_panel/warehouses.html.twig', [
            'warehouses' => $this->adminService->showWarehouses()
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_edit_user')]
    public function editUser(int $id): Response
    {
        return $this->render('admin_panel/useredit.html.twig', [
            'warehouses' => $this->adminService->showAssignedWarehouse($id),
            'user' => $this->adminService->showUser($id)
        ]);
    }

    #[Route('/admin/user/edit/{uid}/{wid}/', name: 'app_add_us_war')]
    public function updateUser(int $wid, int $uid): Response
    {
        $this->adminService->updateMembership($wid, $uid);
        return $this->redirectToRoute('app_edit_user', ['id' => $uid]);
    }

    #[Route('/admin/warehouse/edit/{id}', name: 'app_edit_warehouse')]
    public function editWarehouse(int $id): Response
    {
        return $this->render('admin_panel/warehouseedit.html.twig', [
            'users' => $this->adminService->showAssignedUser($id),
            'warehouse' => $this->adminService->showWarehouse($id)
        ]);
    }

    #[Route('/admin/warehouse/edit/{wid}/{uid}/', name: 'app_add_war_us')]
    public function updateWarehouse(int $wid, int $uid): Response
    {
        $this->adminService->updateMembership($wid, $uid);
        return $this->redirectToRoute('app_edit_warehouse', ['id' => $wid]);
    }

    #[Route('/admin/user/add', name: 'app_add_user')]
    public function addUser(Request $request): Response
    {
        $form = $this->createForm(UserForm::class);

        echo $this->adminService->addUser($form, $request);

        return $this->render('admin_panel/useradd.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/warehouse/add', name: 'app_add_warehouse')]
    public function warehouseAdd(Request $request): Response
    {
        $form = $this->createForm(WarehouseForm::class);

        echo $this->adminService->addWarehouse($form, $request);

        return $this->render('admin_panel/warehouseadd.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/add', name: 'app_add_product')]
    public function productAdd(Request $request): Response
    {
        $form = $this->createForm(ProductForm::class);

        echo $this->adminService->addProduct($form, $request);

        return $this->render('admin_panel/productadd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
