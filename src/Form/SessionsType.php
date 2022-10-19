<?php

namespace App\Form;

use App\Entity\Intern;
use App\Entity\Sessions;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_session', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('starting_date', DateType::class, ['attr'=>['class'=>'form-control'],
                                                    'widget'=>'single_text'])

            ->add('ending_date', DateType::class, ['attr'=>['class'=>'form-control'],
                                                    'widget'=>'single_text'])

            ->add('max_period_day', IntegerType::class, ['attr'=>['class'=>'form-control']])

            ->add('reserved_places', IntegerType::class, ['attr'=>['class'=>'form-control']])

            ->add('total_places', IntegerType::class, ['attr'=>['class'=>'form-control']])
            
            ->add('formation', EntityType::class, ['class'=>Formation::class,
                                                'choice_label'=>'titleFormation',
                                                'attr'=>['class'=>'form-control']])

            ->add('submit', SubmitType::class, ['attr'=>['class'=>'btn btn-dark']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sessions::class,
        ]);
    }
}
