<?php

namespace UserBundle\Form;

use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class EditPasswordFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => ''
            ])
        ;
    }
}
