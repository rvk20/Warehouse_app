<?php

namespace App\Service;

use App\Entity\File;
use App\Entity\ProductProperties;
use App\Entity\ProductState;
use App\Entity\Warehouse;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductPropertiesRepository;
use App\Repository\ProductStateRepository;
use Symfony\Component\HttpFoundation\Request;


class ReceiptProductService
{
    private $entityManager;
    private $stateRepository;
    private $propertiesRepository;
    
    public function __construct(ManagerRegistry $doctrine, ProductStateRepository $stateRepository, ProductPropertiesRepository $propertiesRepository){
        $this->entityManager = $doctrine->getManager();
        $this->stateRepository = $stateRepository;
        $this->propertiesRepository = $propertiesRepository;
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
                    
                    $fname = md5($file->getClientOriginalName()) . $this->propertiesRepository->getLastPropertiesId() . "." . $file->guessExtension();
                    $file->move(
                    $dir,
                    $fname
                    );
                    $f = new File();
                    $f->setFilename($fname);
                    $f->setProductproperties($properties);
                    $this->entityManager->persist($f);
                }
            }
            $this->entityManager->persist($properties);
            $this->entityManager->persist($state);
            $this->entityManager->flush();

            return "Przyjęto produkt.";
        }
    }

    public function showState(Warehouse $warehouse)
    {
        return $state = $this->stateRepository->findBy([
            'warehouse' => $warehouse
            ]
        );
    }

    public function showProperties(Warehouse $warehouse)
    {
        return $properties = $this->propertiesRepository->findBy([
            'warehouse' => $warehouse
            ]
        );
    }

    public function showPropertiesDetails(int $id)
    {
        return $detail = $this->propertiesRepository->find($id);
    }

    public function showFiles(ProductProperties $product)
    {
        return $product->getFiles();
    }

    public function releaseProduct($form, Request $request, Warehouse $warehouse)
    {
        $form->handleRequest($request);
    
        if($form->isSubmitted()) {
            $data = $form->getData();
            $state = $this->stateRepository->findOneBy([
                'warehouse' => $warehouse,
                'product' => $data['product']->getProduct()->getId()
                ]
            );

            if(!$state)
                return "Brak towaru.";

            if($data['quantity'] > $state->getQuantity())
                return "Brak towaru.";

            $state->setQuantity($state->getQuantity() - $data['quantity']);
            $this->entityManager->persist($state);
            $this->entityManager->flush();
            return "Wydano produkt.";
        }
    }
}