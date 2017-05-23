<?php

namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use ToolsBundle\Form\PhoneNumberType;
use ToolsBundle\Form\AddressType;
use UserBundle\Entity\User;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->add('email', EmailType::class, [
                'required' => true,
                'label'    => false
            ])
            ->add('emergencyEmail', EmailType::class, [
                'required' => true,
                'label'    => false
            ])
            ->add('phone', PhoneNumberType::class, [
                'required' => true
            ])
            ->add('addresses', CollectionType::class, [
                'allow_delete' => false,
                'entry_type'   => AddressType::class,
                'allow_add'    => false,
                'required'     => true
            ])
        ;

        if (!strcmp($options['role'], 'ROLE_ADVISOR')) {
             $builder->add('languages', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'property'                  => 'name',
                'multiple'                  => true,
                'expanded'                  => true,
                'required'                  => true,
                'class'                     => 'ToolsBundle:Language',
                'label'                     => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role'       => 'ROLE_CONTRACTOR'
        ]);
    }
}
