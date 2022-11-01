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
        return $this->warehouseRepository->findBy(
            ['user_id' => $userId],
        );
    }
}