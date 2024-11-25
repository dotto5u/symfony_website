<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Enum\ProductStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('name', TextType::class, [
                'label' => 'label.product.name',
                'attr' => [
                    'placeholder' => 'placeholder.product.name',
                    'autocomplete' => 'name',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'label.entity.price',
                'attr' => [
                    'placeholder' => 'placeholder.product.price',
                    'autocomplete' => 'price',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.product.description',
                'attr' => [
                    'placeholder' => 'placeholder.product.description',
                    'autocomplete' => 'description',
                ],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'label.product.stock',
                'attr' => [
                    'placeholder' => 'placeholder.product.stock',
                    'autocomplete' => 'stock',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'label.product.pre_order' => ProductStatus::PRE_ORDER,
                    'label.product.available' => ProductStatus::AVAILABLE,
                    'label.product.sold_out' => ProductStatus::SOLD_OUT,
                ],
                'label' => 'label.entity.status',
                'attr' => [
                    'autocomplete' => 'status',
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'label.product.categories',
                'attr' => [
                    'autocomplete' => 'categories',
                ],
            ])
            ->add('image', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'url',
                'multiple' => false,
                'label' => 'label.product.image',
                'placeholder' => 'placeholder.product.image',
                'attr' => [
                    'autocomplete' => 'image',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $isEdit ? 'label.edit' : 'label.add',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_edit' => false,
        ]);
    }
}