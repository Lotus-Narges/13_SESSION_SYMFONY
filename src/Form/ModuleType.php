<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_module', TextType::class, ['attr'=>['class'=>'form-control']])

            ->add('category', EntityType::class, ['class'=>Category::class,
                                                'choice_label'=>'titleCategory',
                                                'attr'=>['class'=>'form-control']])
                                                
            ->add('submit', SubmitType::class, ['attr'=>['class'=>'btn btn-dark']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
