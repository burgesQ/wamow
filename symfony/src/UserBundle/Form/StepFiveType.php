<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StepFiveType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')

            ->add('experienceShaping', 'collection',
                [
                    'type' => new ExperienceShapingType(),
                ]
            )
            ->add('submit', 'submit',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.submit'
                ]
            )
            ->add('back', 'submit',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.back',
                    'validation_groups' => false,
                ]
            )
            ;
    }
}
