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
            ->add('street', null,
                  array(
                      'label'=>'form.address.street',
                      'required'=>false,
                      'translation_domain' => 'tools'
                  ))
            ->add('street2', null,
                  array(
                      'label'=>'form.address.street',
                      'required'=>false,
                      'translation_domain' => 'tools'
                  ))
            ->add('city', null,
                  array(
                      'label'=>'form.address.city',
                      'required'=>false,
                      'translation_domain' => 'tools'
                  ))
            ->add('zipcode', null,
                  array(
                      'label'=>'form.address.zipcode',
                      'required'=>false,
                      'translation_domain' => 'tools'
                  ))
            ->add('state', null,
                  array(
                      'label'=>'form.address.state',
                      'required'=>false,
                      'translation_domain' => 'tools'
                  ))
            ->add('country', 'country',
                  array(
                      'required' => true,
                      'label'=>'form.address.country',
                      'translation_domain' => 'tools',
                      'placeholder' => 'form.address.choosecountry'
                  ))
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
