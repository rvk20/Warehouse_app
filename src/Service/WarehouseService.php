<?php

namespace App\Service;

use App\Repository\MembershipWarehouseRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;

class WarehouseService
{
    private $warehouseRepository;
    private $memberRepository;
    
    public function __construct(WarehouseRepository $warehouseRepository, UserRepository $userRepository, MembershipWarehouseRepository $memberRepository){
        $this->warehouseRepository = $warehouseRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
    }

    public function showWarehouses(int $userId, string $role)
    {
        if("ROLE_ADMIN" === $role)
            return $this->warehouseRepository->findAll();

        $warehousesReturn = array();
        
        $membership =$this->memberRepository->findBy([
            'user' => $userId,
        ]);

        foreach($membership as $element)
            array_push($warehousesReturn, $element->getWarehouse());

        return $warehousesReturn;
    }

    public function showWarehouse(int $id)
    {
        return $this->warehouseRepository->find($id);
    }

    public function checkAccess(int $userId, int $warehouseId, string $role)
    {
        if("ROLE_ADMIN" === $role)
            return true;

        $membership = $this->memberRepository->findOneBy([
            'warehouse' => $warehouseId,
            'user' => $userId
        ]);
        
        if($membership)
            return true;
        else
            return false;
    }
}