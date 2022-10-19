<?php

namespace App\Form;

use App\Entity\Intern;
use App\Entity\Sessions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('first_name', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('sex', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('birth_date', DateType::class, ['attr'=>['class'=>'form-control'],
                                                'widget'=>'single_text'])

            ->add('city', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('mail', EmailType::class, ['attr'=>['class'=>'form-control']])

            ->add('phone_number', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('submit', SubmitType::class, ['attr'=>['class'=>'btn btn-dark']])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intern::class,
        ]);
    }
}
