<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use UserBundle\Form\RegistrationType;
use ToolsBundle\Form\UploadResumeType;

class MergedFormRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new RegistrationType())
            ->add('resume', new UploadResumeType())
            ->add('save', 'submit')
            ->add('linkedin', 'submit',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'registration.linkedin',
                ]
            )
            ;
    }
}
