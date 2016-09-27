<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Entity\Language;
use ToolsBundle\Form\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

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
            ->add('address')
            ->add('city')
            ->add('zipcode')
            ->add('country',          'country')
            ->add('state')
            ->add('confidentiality',  'checkbox', array(
                'label'    => 'Does this mission has to be confidential?',
                'required' => false,
              ))
            ->add('language',   'entity', array(
                'class' => 'ToolsBundle:Language',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Choose language(s) required',
              ))
            ->add('telecommuting',    'checkbox', array(
                'label'    => 'Requiere physical presence?',
                'required' => false,
              ))
            ->add('international',  'checkbox', array(
                'label'    => 'Is this mission international?',
                'required' => false,
              ))
            ->add('dailyFeesMin')
            ->add('dailyFeesMax')
            ->add('missionBeginning',         'date', array(
                'label'    => 'Start of mission :',
                'data'     => new \Datetime(),
                'years' => range(date('Y'), date('Y') + 5)
              ))
            ->add('missionEnding',            'date', array(
                'label'    => 'End of mission :',
                'data'     => new \Datetime(),
                'years' => range(date('Y'), date('Y') + 5)
              ))
            ->add('applicationEnding',        'date', array(
                'label'    => 'Application deadline :',
                'data'     => new \Datetime(),
                'years' => range(date('Y'), date('Y') + 5)
              ))
            ->add('professionalExpertise',   'entity', array(
                'class' => 'MissionBundle:professionalExpertise',
                'property' => 'name',
                'multiple' => false,
                'label' => 'Choose your expertise',
                'placeholder' => 'Choose an expertise',
              ))
            ->add('missionKind',   'entity', array(
                'class' => 'MissionBundle:missionKind',
                'property' => 'name',
                'multiple' => false,
                'label' => 'Mission kind',
                'placeholder' => 'Choose a kind',
                ))
            ->add('image')
            ->add('save',             'submit')
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
