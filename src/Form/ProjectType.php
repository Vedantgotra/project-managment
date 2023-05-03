<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => ['class' => 'form-control w-25'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Description', TextType::class, [
                'attr' => ['class' => 'form-control w-25'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Technology', TextType::class, [
                'attr' => ['class' => 'form-control w-25'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
