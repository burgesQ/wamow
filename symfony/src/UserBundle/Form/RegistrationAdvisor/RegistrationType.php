<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use ToolsBundle\Form\AddressCountryFormType;
use Symfony\Component\Form\AbstractType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('plainPassword')

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
            ->add('addresses', CollectionType::class, [
                'type'         => AddressCountryFormType::class,
                'required'     => true,
                'allow_add'    => false,
                'allow_delete' => false
            ])
            ->add('remoteWork', CheckboxType::class, [
                'translation_domain'        => 'tools',
                'required'                  => false,
                'label'                     => 'registration.advisor.zero.remote',
            ])

            ->add('languages', Select2EntityType::class, [
                'translation_domain' => 'tools',
                'text_property' => 'name',
                'remote_route'  => 'languages_autocomplete',
                'primary_key'   => 'id',
                'allow_add'     => [
                    'enabled'        => false
                ],
                'multiple'      => true,
                'class'         => 'ToolsBundle\Entity\Language',
                'label'         => 'mission.new.label.language',
            ])
            ->add('linkedinId', TextType::class, [
                'attr'     => [
                    'style' => "display:none"
                ],
                'label'    => false,
                'required' => false
            ]);
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
