<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
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
    private $passEncoder;
    
    public function __construct(ManagerRegistry $doctrine, WarehouseRepository $warehouseRepository, UserRepository $userRepository, UserPasswordHasherInterface $passEncoder){
        $this->entityManager = $doctrine->getManager();
        $this->warehouseRepository = $warehouseRepository;
        $this->userRepository = $userRepository;
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
            return "Użytkownik dodany pomyślnie";
        }
    }
}