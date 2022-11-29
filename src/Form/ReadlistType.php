<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Readlist;
use App\Repository\BookRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReadlistType extends AbstractType
{

    private $token; 

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' =>
            [
                'class' => 'form-control',
                'minlenght' => '1',
                'maxlength' => '50'
            ],
            'label' => 'List Name',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 1, 'max' => 50]),
                new Assert\NotBlank()
            ]
        ])
        

        ->add('description', TextareaType::class, [
            'attr' =>
            [
                'class' => 'form-control'
               
            ],
            'label' => 'List description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
            ])


            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input'],
                    'label_attr' => [ 'form-check-label mt-4'],
                    'required' => false

                ])
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'query_builder' => function (BookRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->where('b.user = :user')
                        ->orderBy('b.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser() ); },
                        'label' =>"Books",
                        'label_attr' => [
                            'class' => 'form-label mt-4'
                        ],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, 
        ['attr' => [
            'class' => 'btn btn-primary my-4' ],
            'label' => 'Créer une lise de lecture'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Readlist::class,
        ]);
    }
}
