<?php

namespace App\Service;

use App\Entity\File;
use App\Entity\ProductProperties;
use App\Entity\ProductState;
use App\Entity\Product;
use App\Entity\Warehouse;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ReceiptProductForm;
use App\Repository\ProductStateRepository;
use Symfony\Component\HttpFoundation\Request;


class ReceiptProductService
{
    private $entityManager;
    private $stateRepository;
    
    public function __construct(ManagerRegistry $doctrine, ProductStateRepository $stateRepository){
        $this->entityManager = $doctrine->getManager();
        $this->stateRepository = $stateRepository;
    }

    public function receiptProduct($form, Request $request, string $dir, Warehouse $warehouse)
    {
        $form->handleRequest($request);
    
        if($form->isSubmitted()) {
            $data = $form->getData();
            $properties = new ProductProperties();
            $properties->setQuantity($data['quantity']);
            $properties->setVat($data['vat']);
            $properties->setPrice($data['price']);
            $properties->setProduct($data['product']);
            $properties->setWarehouse($warehouse);

            $state = $this->stateRepository->findOneBy([
                'product' => $data['product']->getId(),
                'warehouse' => $warehouse
                ]
            );
            if(!$state){
                $state = new ProductState();
                $state->setQuantity($data['quantity']);
                $state->setProduct($data['product']);
                $state->setWarehouse($warehouse);
            }
            else
                $state->setQuantity($state->getQuantity()+$data['quantity']);

            $files = $form['file']->getData();
     
            if($files) {
                if(4 < count($files))
                    return "Zbyt wiele plików, limit 4.";

                foreach ($files as $file) 
                {
                    if($file->guessExtension() !== "xml" && $file->guessExtension() !== "pdf")
                        return "Tylko formaty PDF i XML.";

                    $file->move(
                    $dir,
                    $file->getClientOriginalName()
                    );
                    $f = new File();
                    $f->setFilename($file->getClientOriginalName());
                    $f->setProductproperties($properties);
                    $this->entityManager->persist($f);
                }
            }
            $this->entityManager->persist($properties);
            $this->entityManager->persist($state);
            $this->entityManager->flush();
        }
    }
}