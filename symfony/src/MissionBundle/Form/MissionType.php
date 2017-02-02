<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Entity\Language;
use ToolsBundle\Entity\Address;
use ToolsBundle\Form\AddressType;
use ToolsBundle\Form\FileType;
use ToolsBundle\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;

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
            ->add('resume',  null, array(
                'label' => 'mission.new.form.resume',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('address',    new AddressType(), array(
                'label' => 'form.address.country',
                'translation_domain' => 'ToolsBundle'
                ))
            ->add('confidentiality',  'checkbox', array(
                'required' => false,
                'label' => 'mission.new.form.confidentiality',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('languages', EntityType::class, array(
                'class' => 'ToolsBundle:Language',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'label' => 'language.title',
                'translation_domain' => 'tools',
                'choice_translation_domain' => 'tools',
                'constraints' => new Count(
                    array('min' => 1, 'minMessage' => "error.minlanguage")
                ),
            ))
            ->add('telecommuting',    'checkbox', array(
                'required' => false,
                'label' => 'mission.new.form.telecommuting',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('international',  'checkbox', array(
                'required' => false,
                'label' => 'mission.new.form.international',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('budget', null, array(
                'label' => 'mission.new.form.budget',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('missionBeginning',         'date', array(
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
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'mission.new.form.chooseMonth',
                    'day' => 'mission.new.form.chooseDay',
                    'year' => 'mission.new.form.chooseYear',
                ),
                'format' => 'MMddyyyy',
                'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                'label' => 'mission.new.form.missionEnding',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('applicationEnding',        'date', array(
                'years' => range(date('Y'), date('Y') + 5),
                'placeholder' => array(
                    'month' => 'mission.new.form.chooseMonth',
                    'day' => 'mission.new.form.chooseDay',
                    'year' => 'mission.new.form.chooseYear',
                ),
                'format' => 'MMddyyyy',
                'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                'label' => 'mission.new.form.applicationEnding',
                'translation_domain' => 'MissionBundle'
              ))
            ->add('professionalExpertise', EntityType::class, array(
                'class' => 'MissionBundle:ProfessionalExpertise',
                'property' => 'name',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'professionalexpertises.title',
                'label' => false,
                'translation_domain' => 'MissionBundle',
                'choice_translation_domain' => 'MissionBundle',
            ))
            ->add('missionKind', EntityType::class, array(
                'class' => 'MissionBundle:MissionKind',
                'property' => 'name',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'typemissions.title',
                'label' => false,
                'translation_domain' => 'MissionBundle',
                'choice_translation_domain' => 'MissionBundle',
            ))
            ->add('businessPractice', EntityType::class, array(
                'class' => 'MissionBundle:BusinessPractice',
                'property' => 'name',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'businesspractice.title',
                'label' => false,
                'translation_domain' => 'MissionBundle',
                'choice_translation_domain' => 'MissionBundle',
            ))
            ->add('image')
            ->add('tags', 'entity', array(
                'class' => 'ToolsBundle:Tag',
                'property' => 'tag',
                'placeholder' => 'Add Tags ...',
                'multiple' => true,
                'translation_domain' => 'MissionBundle',
                'required' => false,
                'mapped' => false,
                'csrf_protection' => false,
                'attr' => array('multiple class' => 'chosen-select',
                'style' => 'width: 350px',
                'placeholder_text_multiple' => 'Add Some Tags ...')))
            ->add('save',   'submit', array(
                'label' => 'mission.new.form.submit',
                'translation_domain' => 'MissionBundle'
                ))
            ->get('tags')->resetViewTransformers()
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
