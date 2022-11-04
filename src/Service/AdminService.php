<?php

namespace App\Service;

use App\Entity\MembershipWarehouse;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Warehouse;
use App\Repository\MembershipWarehouseRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminService
{
    private $entityManager;
    private $warehouseRepository;
    private $userRepository;
    private $memberRepository;
    private $passEncoder;
    
    public function __construct(ManagerRegistry $doctrine, WarehouseRepository $warehouseRepository, UserRepository $userRepository, MembershipWarehouseRepository $memberRepository, UserPasswordHasherInterface $passEncoder){
        $this->entityManager = $doctrine->getManager();
        $this->warehouseRepository = $warehouseRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
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
}