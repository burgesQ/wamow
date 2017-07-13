<?php

namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use ToolsBundle\Form\ProfilePictureType;
use Symfony\Component\Form\AbstractType;
use UserBundle\Entity\User;

class EditProfileMergedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EditProfileFormType::class, [
                'role' => $options['role']
            ])
            ->add('image', ProfilePictureType::class)
            ->add('submit', SubmitType::class, [
                'label' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'role'       => null
        ]);
    }
}
