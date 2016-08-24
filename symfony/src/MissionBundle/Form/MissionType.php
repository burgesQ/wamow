<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MissionBundle\Entity\Language;
use MissionBundle\Form\LanguageType;

class MissionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('resume')
            ->add('adress')
            ->add('city')
            ->add('country', 'country')
            ->add('zipcode')
            ->add('minNumberUser')
            ->add('maxNumberUser')
            ->add('confidentiality', 'checkbox', array(
                'label'    => 'Does this mission has to be confidential?',
                'required' => false,
              ))
            ->add('numberStep')
            ->add('state')
            ->add('language', 'entity', array(
                'class' => 'MissionBundle:Language',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true
              ))
            ->add('telecommuting', 'checkbox', array(
                'label'    => 'Does this mission propose telecommuting?',
                'required' => false,
              ))
            ->add('dailyFeesMin')
            ->add('dailyFeesMax')
            ->add('duration')
            ->add('beginning', 'date')
            ->add('image')
            ->add('save',      'submit')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MissionBundle\Entity\Mission'
        ));
    }
}
