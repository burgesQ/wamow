<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\FormBuilderInterface;

class StepTwoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')

            ->add('professionalExpertise', 'entity',
                [
                    'class' => 'MissionBundle:ProfessionalExpertise',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => false,
                    'translation_domain' => 'MissionBundle',
                    'choice_translation_domain' => 'MissionBundle',
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

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

}
