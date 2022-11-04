<?php

namespace App\Service;

use App\Entity\MembershipWarehouse;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Warehouse;
use App\Repository\MembershipWarehouseRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminService
{
    private $warehouseRepository;
    private $userRepository;
    private $memberRepository;
    private $productRepository;
    private $passEncoder;
    
    public function __construct(ProductRepository $productRepository, WarehouseRepository $warehouseRepository, UserRepository $userRepository, MembershipWarehouseRepository $memberRepository, UserPasswordHasherInterface $passEncoder){
        $this->warehouseRepository = $warehouseRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
        $this->productRepository = $productRepository;
        $this->passEncoder = $passEncoder;
    }

    public function addUser($form, Request $request)
    {
        $form->handleRequest($request);
    
        if($form->isSubmitted()) {
            $data = $form->getData();

            if($this->userRepository->findOneBy(['username' => $data['username']]))
                return "Użytkownik już istnieje.";

            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $this->passEncoder->hashPassword($user, $data['password'])
            );
            $user->setRoles(["ROLE_USER"]);
            $this->userRepository->save($user, true);

            if($data['warehouse'])
                foreach($data['warehouse'] as $element) {
                    $membership = new MembershipWarehouse();
                    $membership->setWarehouse($element);
                    $membership->setUser($user);
                    $this->memberRepository->save($membership, true);
                }

            return "Użytkownik dodany pomyślnie";
        }
    }

    public function addWarehouse($form, Request $request)
    {
        $form->handleRequest($request);
    
        if($form->isSubmitted()) {          
            $data = $form->getData();

            $warehouse = new Warehouse();
            $warehouse->setName($data['name']);
            $this->warehouseRepository->save($warehouse, true);

            if($data['user'])
                foreach($data['user'] as $element) {
                    $membership = new MembershipWarehouse();
                    $membership->setWarehouse($warehouse);
                    $membership->setUser($element);
                    $this->memberRepository->save($membership, true);
                }

            return "Magazyn dodany pomyślnie";
        }
    }

    public function addProduct($form, Request $request)
    {
        $form->handleRequest($request);
    
        if($form->isSubmitted()) {
            $data = $form->getData();

            $product = new Product();
            $product->setName($data['name']);
            $product->setUnit($data['unit']);
            $this->productRepository->save($product, true);

            return "Produkt dodany pomyślnie";
        }
    }

    public function showUsers()
    {
        return $this->userRepository->findAll();
    }

    public function showWarehouses()
    {
        return $this->warehouseRepository->findAll();
    }

    public function showUser(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function showWarehouse(int $id)
    {
        return $this->warehouseRepository->find($id);
    }

    public function showAssignedWarehouse(int $id)
    {
        $membership = $this->warehouseRepository->findAll();

        foreach($this->warehouseRepository->findAll() as $warehouse)
            foreach($this->memberRepository->showAssignedWarehouse($id) as $member)
                if($member['warehouse_id'] === $warehouse->getId())
                    unset($membership[$warehouse->getId()-1]);
        
        return $membership;
    }

    public function showAssignedUser(int $id)
    {
        $membership = $this->userRepository->findAll();

        foreach($this->userRepository->findAll() as $user)
            foreach($this->memberRepository->showAssignedUser($id) as $member)
                if($member['user_id'] === $user->getId())
                    unset($membership[$user->getId()-1]);
        
        return $membership;
    }

    public function updateMembership(int $wid, int $uid)
    {
        $membership = new MembershipWarehouse();
        $membership->setUser($this->userRepository->find($uid));
        $membership->setWarehouse($this->warehouseRepository->find($wid));
        $this->memberRepository->save($membership, true);
        return true;
    }
}