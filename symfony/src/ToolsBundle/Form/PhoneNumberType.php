<?php

namespace ToolsBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PhoneNumberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prefix', EntityType::class, [
                'placeholder' => 'form.phone.chooseprefix',
                'property'    => 'country',
                'class'       => 'ToolsBundle\Entity\PrefixNumber',
                'label'       => false,
            ])
            ->add('number', null, [
                'required' => true,
                'label'    => false
                // TODO ADD REGEX CONSTRAINT
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ToolsBundle\Entity\PhoneNumber'
        ]);
    }
}
