<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Sessions;
use App\Entity\SessionCours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionCoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('period_day', IntegerType::class, ['attr'=>['class'=>'form-control']])

            ->add('sessions', EntityType::class, ['class'=>Sessions::class,
                                                    'choice_label'=>'titleSession',
                                                    'attr'=>['class'=>'form-control']])

            ->add('module', EntityType::class, ['class'=>Module::class,
                                                'mapped' => false,
                                                'choice_label'=>'titleModule',
                                                'attr'=>['class'=>'form-control']])
                                                
            ->add('submit', SubmitType::class, ['attr'=>['class'=>'btn btn-dark']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionCours::class,
            'mapped' => false
        ]);
    }
}
