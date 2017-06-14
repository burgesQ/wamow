<?php

namespace MissionBundle\Form\MissionGenerator;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;

class StepThreeFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('missionKinds', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'placeholder'               => 'typemissions.title',
                'choice_label'              => 'name',
                'multiple'                  => true,
                'expanded'                  => true,
                'required'                  => true,
                'class'                     => 'MissionBundle:MissionKind',
                'label'                     => false,
                'query_builder'             => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'ASC');
                },
            ])
            ->add('certifications', Select2EntityType::class, [
                'translation_domain' => 'tools',
                'text_property'      => 'name',
                'remote_route'       => 'certifications_autocomplete',
                'primary_key'        => 'id',
                'allow_add'          => [
                    'enabled'        => true,
                    'new_tag_text'   => ' (NEW)',
                    'new_tag_prefix' => '__',
                    'tag_separators' => '[""]'
                ],
                'multiple'           => true,
                'class'              => 'MissionBundle\Entity\Certification',
                'label'              => 'mission.new.label.certification'
            ])
            ->add('languages', CollectionType::class, [
                'allow_add'          => true,
                'allow_delete'       => true,
                'entry_type'         => EntityType::class,
                'translation_domain' => 'tools',
                'required'           => true,
                'label'              => false,
                'entry_options'      => [
                    'choice_translation_domain' => 'tools',
                    'translation_domain'        => 'tools',
                    'choice_label'              => 'name',
                    'required'                  => true,
                    'class'                     => 'ToolsBundle:Language',
                    'label'                     => '',
                ],
                'attr' => [
                    'class' => 'col-xs-10 col-md-6 required'
                ]
            ])
            ->add('workExperience', EntityType::class, [
                'choice_translation_domain' => 'tools',
                'translation_domain'        => 'tools',
                'placeholder'               => 'typemissions.title',
                'choice_label'              => 'name',
                'multiple'                  => false,
                'expanded'                  => false,
                'required'                  => true,
                'class'                     => 'MissionBundle:WorkExperience',
                'label'                     => false,
            ])
            ->add('price', RangeType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.label.range'
            ])
            ->add('forLater', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => 'mission.new.label.later',
                'attr'               => [
                    'style' => $options['stepFour']
                ]
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'tools',
                'validation_groups'  => false,
                'label'              => $options['labelBack']
            ])
            ->add('next', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => $options['labelNext']
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
