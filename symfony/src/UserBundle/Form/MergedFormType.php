<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use ToolsBundle\Form\UploadType;
use UserBundle\Form\ProfileFormType;
use UserBundle\Entity\User;

class MergedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new ProfileFormType('UserBundle\Entity\User'))
            ->add('image', new UploadType())
            ->add('resume', new UploadType())
            ;
    }
}
