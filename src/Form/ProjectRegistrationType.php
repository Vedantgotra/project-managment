<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder->add('Teacher', EntityType::class, [
        //     'class' => Project::class,
        //     'choices' => $options['recommended_product'],
        //     'choice_label' => function (Product $user) {
        //         return $user->getProduct();
        //     }
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'teacher' => array(),
        ]);
    }
}
