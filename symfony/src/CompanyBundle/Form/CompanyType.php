<?php

namespace CompanyBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('name', TextareaType::class, array(
                'required' => true,
                'label' => 'company.new.name',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('size', ChoiceType::class, array(
                'translation_domain' => 'CompanyBundle',
                'choices' => array(
                    'company.new.small' => '0',
                    'company.new.medium' => '1',
                    'company.new.big' => '2',
                ),
                'choices_as_values' => true,
                'required'    => true,
                'label' => 'company.new.size',
                'placeholder' => 'company.new.choosesize',
                'empty_data'  => null
            ))
            ->add('logo', TextType::class, array(
                'required' => true,
                'label' => 'company.new.logo',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('resume', TextareaType::class, array(
                'required' => true,
                'label' => 'company.new.description',
                'translation_domain' => 'CompanyBundle',
            ))
            ->add('businessPractice', EntityType::class, array(
                'class' => 'MissionBundle:BusinessPractice',
                'property' => 'name',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'businesspractice.title',
                'label' => 'businesspractice.title',
                'translation_domain' => 'MissionBundle',
                'choice_translation_domain' => 'MissionBundle',
            ))
            ->add('save', SubmitType::class, array(
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
