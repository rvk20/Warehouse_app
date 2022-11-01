<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\WarehouseRepository;
use Doctrine\Persistence\ManagerRegistry;

class WarehouseService
{
    private $entityManager;
    private $warehouseRepository;
    
    public function __construct(ManagerRegistry $doctrine, WarehouseRepository $warehouseRepository){
        $this->entityManager = $doctrine->getManager();
        $this->warehouseRepository = $warehouseRepository;
    }

    public function showWarehouses(int $userId, string $role)
    {
        if("ROLE_ADMIN" === $role)
            return $this->warehouseRepository->findAll();

        return $this->warehouseRepository->findBy(
            ['user' => $userId],
        );
    }

    public function showWarehouse(int $id)
    {
        return $this->warehouseRepository->find($id);
    }

    public function checkAccess(int $userId, int $warehouseId, string $role)
    {
        if("ROLE_ADMIN" === $role)
            return true;
        
        if($this->warehouseRepository->find($warehouseId)->getUser()->getId() === $userId)
            return true;
        else
            return false;
    }
}