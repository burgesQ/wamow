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
                'choice_translation_domain' => 'MissionBundle',
                'translation_domain'        => 'MissionBundle',
                'choice_label'              => 'name',
                'placeholder'               => 'businesspractice.title',
                'multiple'                  => false,
                'expanded'                  => true,
                'class'                     => 'MissionBundle:BusinessPractice',
                'label'                     => true
            ])
            ->add('professionalExpertise', EntityType::class, [
                'choice_translation_domain' => 'MissionBundle',
                'translation_domain'        => 'MissionBundle',
                'choice_label'              => 'name',
                'placeholder'               => '',
                'multiple'                  => false,
                'class'                     => 'MissionBundle:ProfessionalExpertise',
                'label'                     => 'mission.new.form.professional_expertise'
            ])
            ->add('budget', IntegerType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => 'mission.new.form.budget'
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
            ->add('missionBeginning', DateType::class, [
                'translation_domain' => 'MissionBundle',
                'placeholder'        => [
                    'month' => 'mission.new.form.chooseMonth',
                    'year'  => 'mission.new.form.chooseYear',
                    'day'   => 'mission.new.form.chooseDay'
                ],
                'pattern'            => "{{ month }}/{{ day }}/{{ year }}",
                'format'             => 'MMddyyyy',
                'label'              => 'mission.new.form.missionBeginning',
                'years'              => range(date('Y'), date('Y') + 5)
            ])
            ->add('missionEnding', DateType::class, [
                'translation_domain' => 'MissionBundle',
                'placeholder'        => [
                    'month' => 'mission.new.form.chooseMonth',
                    'year'  => 'mission.new.form.chooseYear',
                    'day'   => 'mission.new.form.chooseDay'
                ],
                'pattern'            => "{{ month }}/{{ day }}/{{ year }}",
                'format'             => 'MMddyyyy',
                'label'              => 'mission.new.form.missionEnding',
                'years'              => range(date('Y'), date('Y') + 5)
            ])
            ->add('applicationEnding', DateType::class, [
                'translation_domain' => 'MissionBundle',
                'placeholder'        => [
                    'month' => 'mission.new.form.chooseMonth',
                    'year'  => 'mission.new.form.chooseYear',
                    'day'   => 'mission.new.form.chooseDay'
                ],
                'pattern'            => "{{ month }}/{{ day }}/{{ year }}",
                'format'             => 'MMddyyyy',
                'label'              => 'mission.new.form.applicationEnding',
                'years'              => range(date('Y'), date('Y') + 5)
            ])
            ->add('confidentiality', CheckboxType::class, [
                'translation_domain' => 'MissionBundle',
                'required'           => false,
                'label'              => 'mission.new.form.confidentiality'
            ])
            ->add('telecommuting', CheckboxType::class, [
                'translation_domain' => 'MissionBundle',
                'required'           => false,
                'label'              => 'mission.new.form.telecommuting'
            ])
            ->add('address', AddressCountryFormType::class)

            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => 'mission.new.form.later',
                'attr'               => [
                    'style' => $options['stepFour']
                ]
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'validation_groups'  => false,
                'label'              => $options['labelBack']
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'MissionBundle',
                'label'              => $options['labelNext']
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission',
            'stepFour'   => 'display: all;',
            'labelBack'  => 'mission.new.form.back',
            'labelNext'  => 'mission.new.form.next'
        ]);
    }
}
