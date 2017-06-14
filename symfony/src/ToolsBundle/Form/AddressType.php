<?php

namespace ToolsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', null, [
                'translation_domain' => 'tools',
                'required'           => true,
                'label'              => 'form.address.number',
                'attr'               => [
                    'placeholder' => 'form.address.number'
                ]
            ])
            ->add('street', null, [
                'translation_domain' => 'tools',
                'required'           => true,
                'label'              => 'form.address.street',
                'attr'               => [
                    'placeholder' => 'form.address.street'
                ]
            ])
            ->add('street2', null, [
                'translation_domain' => 'tools',
                'required'           => false,
                'label'              => 'form.address.street',
                'attr'               => [
                    'placeholder' => 'form.address.street_2'
                ]
            ])
            ->add('city', null, [
                'translation_domain' => 'tools',
                'required'           => true,
                'label'              => 'form.address.city',
                'attr'               => [
                    'placeholder' => 'form.address.city'
                ]
            ])
            ->add('zipcode', null, [
                'translation_domain' => 'tools',
                'required'           => false,
                'label'              => 'form.address.zipcode',
                'attr'               => [
                    'placeholder' => 'form.address.zipcode'
                ]
            ])
            ->add('state', null, [
                'translation_domain' => 'tools',
                'required'           => false,
                'label'              => 'form.address.state',
                'attr'               => [
                    'placeholder' => 'form.address.state'
                ]
            ])
            ->add('country', CountryType::class, [
                'translation_domain' => 'tools',
                'required'           => true,
                'label'              => 'form.address.country',
                'placeholder'        => 'form.address.choosecountry',

            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ToolsBundle\Entity\Address'
        ]);
    }
}
