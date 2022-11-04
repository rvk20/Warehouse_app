<?php

namespace App\Form;

use App\Entity\ProductState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReleaseProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => ProductState::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.product', 'ASC')
                        ;
                },
                'choice_label' => 'product.name', 'choice_value' => 'product.id', 'label' => 'nazwa',
            ])
            ->add('quantity', TextType::class, ['label' => 'ilość'])
            ->add('save', SubmitType::class, ['label' => 'wydaj'])
        ;
    }
}