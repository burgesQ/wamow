<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')

            ->add('country',  'country',
                [
                    'required' => true,
                    'label'=> 'form.address.country',
                    'placeholder' => 'form.address.choosecountry',
                    'translation_domain' => 'tools',
                    'choice_translation_domain' => 'tools',
                ]
            )
            ->add('firstName', 'text',
                  [
                      'required' => true
                  ]
            )
            ->add('lastName', 'text',
                [
                    'required' => true
                ]
            )
            ->add('email', 'text',
                [
                    'required' => false
                ]
            )
            ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getFirstName()
    {
        return $this->getBlockPrefix();
    }

    public function getLastName()
    {
        return $this->getBlockPrefix();
    }
}
