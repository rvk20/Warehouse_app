<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReceiptProductForm extends AbstractType
{
    private $product;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->product = $doctrine->getRepository(Product::class)->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('quantity', TextType::class)
            ->add('vat', TextType::class)
            ->add('price', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }
}