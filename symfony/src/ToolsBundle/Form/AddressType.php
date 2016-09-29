<?php

namespace ToolsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'country', array(
                'required' => true,
                'placeholder' => 'Choose a country'))
            ->add('state')
            ->add('zipcode')
            ->add('city')
            ->add('street')
            ->add('street2')
                'placeholder' => 'Choose a country')
            )
            ->add('label')
            ->add('name')
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ToolsBundle\Entity\Address'
        ));
    }
}
