<?php

namespace MissionBundle\Form\MissionGenerator;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class StepFourFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edit', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'form.btn.edit'
            ])
            ->add('edit_1', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'form.btn.edit'
            ])
            ->add('edit_2', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'form.btn.edit'
            ])
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'form.btn.later'
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'form.btn.next'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission'
        ]);
    }
}
