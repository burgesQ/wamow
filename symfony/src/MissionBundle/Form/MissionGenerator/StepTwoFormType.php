<?php

namespace MissionBundle\Form\MissionGenerator;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use ToolsBundle\Form\AddressCountryFormType;
use Symfony\Component\Form\AbstractType;

class StepTwoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('businessPractice', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'choice_label'              => 'name',
                'placeholder'               => 'businesspractice.title',
                'multiple'                  => false,
                'expanded'                  => true,
                'class'                     => 'MissionBundle:BusinessPractice',
                'label'                     => true,
                'required'                  => true
            ])
            ->add('professionalExpertise', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'choice_label'              => 'name',
                'multiple'                  => false,
                'placeholder'               => 'professionalexpertises.title',
                'class'                     => 'MissionBundle:ProfessionalExpertise',
                'label'                     => 'professionalexpertises.title',
                'required'                  => true
            ])
            ->add('currency', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'choice_label'              => 'code',
                'multiple'                  => false,
                'class'                     => 'Lexik\Bundle\CurrencyBundle\Entity\Currency',
                'label'                     => 'legends.currency',
                'required'                  => true
            ])
            ->add('budget', IntegerType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.label.budget',
                'required'           => true,
                'attr' => [
                    'value' => 1,
                    'max_value' => (PHP_INT_MAX - 1)
                ],


            ])
            ->add('continents', EntityType::class, [
                'class'                     => 'MissionBundle:Continent',
                'property'                  => 'name',
                'multiple'                  => true,
                'required'                  => true,
                'expanded'                  => true,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools',
                'constraints'               => new Count([
                    'min'        => 1,
                    'minMessage' => 'user.continent.min',
                ])
            ])

            ->add('applicationEnding', DateType::class, [
                'translation_domain' => 'tools',
                'format'             => 'MM/dd/yyyy',
                'label'              => 'mission.new.label.applicationEnding',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'myDatePicker'
                ]
            ])
            ->add('missionBeginning', DateType::class, [
                'translation_domain' => 'tools',
                'format'             => 'MM/dd/yyyy',
                'label'              => 'mission.new.label.missionBeginning',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'myDatePicker'
                ]
            ])
            ->add('missionEnding', DateType::class, [
                'translation_domain' => 'tools',
                'format'             => 'MM/dd/yyyy',
                'label'              => 'mission.new.label.missionEnding',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'myDatePicker'
                ]
            ])

            ->add('confidentiality', CheckboxType::class, [
                'required' => false,
                'label'    => false,
            ])
            ->add('telecommuting', CheckboxType::class, [
                'required' => false,
                'label'    => false,
            ])
            ->add('address', AddressCountryFormType::class)
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'mission.new.label.later',
                'attr'               => [
                    'style' => $options['stepFour']
                ]
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => $options['labelNext']
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => $options['labelBack']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission',
            'stepFour'   => 'display: all;',
            'labelBack'  => 'form.btn.back',
            'labelNext'  => 'form.btn.next'
        ]);
    }
}
