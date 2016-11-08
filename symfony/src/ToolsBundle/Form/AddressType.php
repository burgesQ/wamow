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
            ->add('country', 'country', array(
                'required' => true,
                'placeholder' => 'mission.new.form.chooseCountry',
                'label' => 'mission.new.form.country',
                'translation_domain' => 'MissionBundle'
            ))
            ->add('state',  'text', array(
                'label' => 'mission.new.form.state',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('zipcode',  'text', array(
                'label' => 'mission.new.form.zipcode',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('city',  'text', array(
                'label' => 'mission.new.form.city',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('street',  'text', array(
                'label' => 'mission.new.form.street',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('street2',  'text', array(
                'label' => 'mission.new.form.street2',
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
            'data_class' => 'ToolsBundle\Entity\Address'
        ));
    }
}
