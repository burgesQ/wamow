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
            ->add('confidentiality', CheckboxType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => 'mission.view.step_four.confidentiality',
                'required'           => false
            ])
            ->add('telecommuting', CheckboxType::class, [
                'translation_domain' => 'MissionBundle',
                'required'           => false,
                'label'              => 'mission.view.step_four.telecommuting'
            ])
            ->add('edit', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.edit'
            ])
            ->add('edit_1', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.edit'
            ])
            ->add('edit_2', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.edit'
            ])
            ->add('print', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.print'
            ])
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.later'
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.back'
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => 'mission.new.form.next'
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
