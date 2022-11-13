<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
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
                'label' => 'Book Name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 1, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('author', TextType::class, [
                'attr' =>
                [
                    'class' => 'form-control',

                ],
                'label' => 'Author name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('year', DateType::class, [
                'widget' => 'single_text',
                'attr' =>
                [
                    'class' => 'form-control',

                ],
                'label' => 'Published date',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'attr' =>
                [
                    'class' => 'btn btn-primary mt-4'

                ],
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
