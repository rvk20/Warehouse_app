<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

class ReceiptProductService
{
    private $entityManager;
    
    public function __construct(ManagerRegistry $doctrine){
        $this->entityManager = $doctrine->getManager();
    }

    public function receiptProduct()
    {
        $product = new Product();
    }
}