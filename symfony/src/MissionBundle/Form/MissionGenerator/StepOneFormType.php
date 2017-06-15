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
                'translation_domain' => 'tools',
                'label'              => 'mission.new.label.title',
                'required'           => true
            ])
            ->add('resume', TextareaType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.label.resume',
                'required'           => true
            ])
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'form.btn.later',
                'attr'               => [
                    'style' => $options['stepFour']
                ]
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => $options['labelNext']
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => $options['labelBack']
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
            'labelBack'  => 'form.btn.cancel_mission',
            'labelNext'  => 'form.btn.next'
        ]);
    }
}
