<?php

namespace App\Form;

use App\Entity\Warehouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Nazwa użytkownika'])
            ->add('password', PasswordType::class, ['label' => 'Hasło'])
            ->add('warehouse', EntityType::class, [
                'class' => Warehouse::class, 'multiple' => true, 'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC')
                        ;
                },
                'choice_label' => 'name',  'label' => 'nazwa'
            ])
            ->add('save', SubmitType::class, ['label' => 'dodaj'])
        ;
    }
}