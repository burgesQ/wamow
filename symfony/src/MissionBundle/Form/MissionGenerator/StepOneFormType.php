<?php

namespace MissionBundle\Form\MissionGenerator;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class StepOneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => 'mission.new.form.title',
                'required'           => true
            ])
            ->add('resume', TextareaType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => 'mission.new.form.resume',
                'required'           => true
            ])
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.later',
                'attr'           =>[
                    'style' => $options['stepFour']
                ]
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => $options['labelBack']
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => $options['labelNext']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission',
            'stepFour'   => 'display: all;',
            'labelBack'  => 'mission.new.form.cancel',
            'labelNext'  => 'mission.new.form.next'
        ]);
    }
}
