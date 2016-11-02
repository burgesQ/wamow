<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Entity\Language;
use ToolsBundle\Entity\Address;
use ToolsBundle\Form\AddressType;
use ToolsBundle\Form\FileType;
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
            ->add('address',    new AddressType())
            ->add('confidentiality',  'checkbox', array(
                'label'    => 'Does this mission has to be confidential?',
                'required' => false,
              ))
            ->add('languages',   'entity', array(
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
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'Month',
                    'day' => 'Day',
                    'year' => 'Year',
                ),
                'format' => 'MMddyyyy',
                'pattern' => "{{ month }}/{{ day }}/{{ year }}",
              ))
            ->add('missionEnding',            'date', array(
                'label'    => 'End of mission :',
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'Month',
                    'day' => 'Day',
                    'year' => 'Year',
                ),
                'format' => 'MMddyyyy',
              ))
            ->add('applicationEnding',        'date', array(
                'label'    => 'Application deadline :',
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'Month',
                    'day' => 'Day',
                    'year' => 'Year',
                ),
                'format' => 'MMddyyyy',
              ))
            ->add('professionalExpertise',   'entity', array(
                'class' => 'MissionBundle:ProfessionalExpertise',
                'property' => 'name',
                'multiple' => false,
                'label' => 'Choose your expertise',
                'placeholder' => 'Choose an expertise',
              ))
            ->add('missionKind',   'entity', array(
                'class' => 'MissionBundle:MissionKind',
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
