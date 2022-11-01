<?php

namespace App\Controller;

use App\Form\ReceiptProductForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
    }

    #[Route('/panel', name: 'app_user_panel')]
    public function index(Request $request): Response
    {
        $text = "abc";
        $form = $this->createForm(ReceiptProductForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $data = $form->getData();
            $text = $data['product']->getId();
            $files = $form['file']->getData();
 
            if ($files) 
            {
                foreach ($files as $file) 
                {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $file->getClientOriginalName()
                    );
                }
            }
        }

        return $this->render('user_panel/index.html.twig', [
            'controller_name' => $text,
            'form' => $form->createView()
        ]);
    }
}
