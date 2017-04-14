<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use FOS\UserBundle\Util\LegacyFormHelper;
use ToolsBundle\Entity\Language;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->add('firstName', TextType::class, [
                'translation_domain'        => 'tools',
                'required'                  => true,
                'label'                     => 'registration.advisor.zero.firstname',
            ])
            ->add('lastName', TextType::class, [
                'translation_domain'        => 'tools',
                'required'                  => true,
                'label'                     => 'registration.advisor.zero.lastname',
            ])
            ->add('email', EmailType::class, [
                'translation_domain'        => 'tools',
                'required'                  => true,
                'label'                     => 'registration.advisor.zero.email',
            ])
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), [
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'tools'),
                'first_options' => array('label' => 'registration.advisor.zero.password'),
                'second_options' => array('label' => 'registration.advisor.zero.repeatpassord'),
                'invalid_message' => 'registration.advisor.zero.mismatchpassword',
            ])
            ->add('country', CountryType::class, [
                'translation_domain'        => 'tools',
                'placeholder'               => 'registration.advisor.zero.country',
                'required'                  => true,
                'label'                     => false
            ])
            ->add('remoteWork', CheckboxType::class, [
                'translation_domain'        => 'tools',
                'required'                  => false,
                'label'                     => 'registration.advisor.zero.remote',
            ])
            ->add('languages', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'choice_label'              => 'name',
                'multiple'                  => true,
                'expanded'                  => true,
                'required'                  => true,

                'class'                     => 'ToolsBundle:Language',
                'label'                     => 'registration.advisor.zero.languages',
            ])
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getFirstName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getLastName()
    {
        return $this->getBlockPrefix();
    }
}
