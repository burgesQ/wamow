<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;

class StepFourType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')

            ->add('workExperience', 'entity',
                [
                    'class' => 'MissionBundle:WorkExperience',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => false,
                    'translation_domain' => 'MissionBundle',
                    'choice_translation_domain' => 'MissionBundle',
                    'constraints' => new Count(
                        [
                            'min' => 5,
                            'max' => 10
                        ]
                    ),
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
