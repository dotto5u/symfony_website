<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.login.email',
                'attr' => [
                    'placeholder' => 'placeholder.login.email',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'label.login.password',
                'attr' => [
                    'placeholder' => 'placeholder.login.password',
                    'autocomplete' => 'current-password',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'label.login.submit',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
