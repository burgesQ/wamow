<?php

namespace CompanyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CompanyBundle\Entity\Sector;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',  'text', array(
                'required' => true,
                'label' => 'company.new.name',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('size', ChoiceType::class, array(
                'translation_domain' => 'CompanyBundle',
                'choices' => array(
                    '0' => 'company.new.small',
                    '1' => 'company.new.medium',
                    '2' => 'company.new.big',
                ),
                'required'    => true,
                'label' => 'company.new.size',
                'placeholder' => 'company.new.choosesize',
                'empty_data'  => null
            ))
            ->add('logo',  'text', array(
                'required' => true,
                'label' => 'company.new.logo',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('resume',  'textarea', array(
                'required' => true,
                'label' => 'company.new.description',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('sector',   'entity', array(
                'class' => 'CompanyBundle:Sector',
                'property' => 'name',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'company.new.choosesector',
                'label' => 'company.new.sector',
                'translation_domain' => 'CompanyBundle'
            ))
            ->add('save',  'submit', array(
                'label' => 'company.new.submit',
                'translation_domain' => 'CompanyBundle',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CompanyBundle\Entity\Company'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'companybundle_company';
    }
}
