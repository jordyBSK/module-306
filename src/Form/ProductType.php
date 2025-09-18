<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price (€)',
                'scale' => 2,
                'attr' => ['class' => 'form-control', 'step' => '0.01']
            ])
            ->add('image', TextType::class, [
                'label' => 'Image URL',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('size', ChoiceType::class, [
                'label' => 'Size',
                'choices' => [
                    'XS' => 'XS',
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('team', TextType::class, [
                'label' => 'Team',
                'attr' => ['class' => 'form-control']
            ])
            ->add('season', TextType::class, [
                'label' => 'Season',
                'attr' => ['class' => 'form-control', 'placeholder' => 'e.g., 2023-24']
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock Quantity',
                'attr' => ['class' => 'form-control']
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}