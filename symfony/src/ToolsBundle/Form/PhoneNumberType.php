<?php

namespace ToolsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use ToolsBundle\Entity\PrefixNumber;

class PhoneNumberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prefix', 'entity', array(
                'class' => 'ToolsBundle:PrefixNumber',
                'property' => 'country',
                'multiple' => false,
                'placeholder' => 'form.phone.chooseprefix',
                'label'=>'form.phone.prefix',
                'translation_domain' => 'tools',
            ))
            ->add('number', null,
                array(
                'label'=>'form.phone.number',
                'required'=>true,
                'translation_domain' => 'tools'
            ))
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ToolsBundle\Entity\PhoneNumber'
        ));
    }
}
