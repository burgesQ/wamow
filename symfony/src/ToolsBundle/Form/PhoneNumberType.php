<?php

namespace ToolsBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use ToolsBundle\Entity\PrefixNumber;

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
                'class'              => 'ToolsBundle:PrefixNumber',
                'property'           => 'countryAndPrefix',
                'multiple'           => false,
                'placeholder'        => 'form.phone.prefix',
                'label'              => false,
                'translation_domain' => 'tools',
                'required'           => true,
                'preferred_choices' => function(PrefixNumber $prefix) {
                    return in_array($prefix->getCountry(), ["FR", "DE", "US", "GB", "ES"]);
                }
            ])
            ->add('number', null, [
                'required'           => true,
                'label'              => false,
                'translation_domain' => 'tools',
                'pattern'            => '/^\(0\)[0-9]*$',
                'attr'               => [
                    'placeholder' => 'form.phone.number',
                ]
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
