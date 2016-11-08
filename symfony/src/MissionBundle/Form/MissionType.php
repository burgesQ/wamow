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
            ->add('title',  'text', array(
                'label' => 'mission.new.form.title',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('resume',  'text', array(
                'label' => 'mission.new.form.resume',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('address',    new AddressType(), array(
                'label' => 'form.address.country',
                'translation_domain' => 'ToolsBundle'
                ))
            ->add('confidentiality',  'checkbox', array(
                'label'    => 'Does this mission has to be confidential?',
                'required' => false,
                'label' => 'mission.new.form.confidentiality',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('languages',   'entity', array(
                'class' => 'ToolsBundle:Language',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'mission.new.form.languages',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('telecommuting',    'checkbox', array(
                'label'    => 'Requiere physical presence?',
                'required' => false,
                'label' => 'mission.new.form.telecommuting',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('international',  'checkbox', array(
                'label'    => 'Is this mission international?',
                'required' => false,
                'label' => 'mission.new.form.international',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('dailyFeesMin', null, array(
                'label' => 'mission.new.form.daily_fees_min',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('dailyFeesMax', null, array(
                'label' => 'mission.new.form.daily_fees_max',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('missionBeginning',         'date', array(
                'label'    => 'Start of mission :',
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'mission.new.form.chooseMonth',
                    'day' => 'mission.new.form.chooseDay',
                    'year' => 'mission.new.form.chooseYear',
                ),
                'format' => 'MMddyyyy',
                'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                'label' => 'mission.new.form.missionBeginning',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('missionEnding',            'date', array(
                'label'    => 'End of mission :',
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'mission.new.form.chooseMonth',
                    'day' => 'mission.new.form.chooseDay',
                    'year' => 'mission.new.form.chooseYear',
                ),
                'format' => 'MMddyyyy',
                'label' => 'mission.new.form.missionEnding',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('applicationEnding',        'date', array(
                'label'    => 'Application deadline :',
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'mission.new.form.chooseMonth',
                    'day' => 'mission.new.form.chooseDay',
                    'year' => 'mission.new.form.chooseYear',
                ),
                'format' => 'MMddyyyy',
                'label' => 'mission.new.form.applicationEnding',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('professionalExpertise',   'entity', array(
                'class' => 'MissionBundle:ProfessionalExpertise',
                'property' => 'name',
                'multiple' => false,
                'placeholder' => 'mission.new.form.chooseExpertise',
                'label' => 'mission.new.form.professionalExpertise',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('missionKind',   'entity', array(
                'class' => 'MissionBundle:MissionKind',
                'property' => 'name',
                'multiple' => false,
                'label' => 'Mission kind',
                'placeholder' => 'mission.new.form.chooseMissionKind',
                'label' => 'mission.new.form.missionKind',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('image')
            ->add('save',   'submit', array(
                'label' => 'mission.new.form.submit',
                'translation_domain' => 'MissionBundle'
                ))
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
