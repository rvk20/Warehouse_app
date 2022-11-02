<?php

namespace App\Form;

use App\Entity\File as EntityFile;
use App\Entity\Product;
use App\Entity\ProductState;
use App\Entity\Warehouse;
use App\Repository\ProductStateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\File;

class UserForm extends AbstractType
{
    private $stateRepository;

    public function __construct(ManagerRegistry $doctrine, ProductStateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Nazwa użytkownika'])
            ->add('password', PasswordType::class, ['label' => 'Hasło'])
            ->add('product', EntityType::class, [
                'attr' => ['size' => '4', 'multiple' => true],
                'class' => Warehouse::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC')
                        ;
                },
                'choice_label' => 'name',  'label' => 'nazwa'
            ])
            ->add('save', SubmitType::class, ['label' => 'przyjmij'])
        ;
    }
}