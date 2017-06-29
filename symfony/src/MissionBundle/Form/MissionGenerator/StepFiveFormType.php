<?php

namespace MissionBundle\Form\MissionGenerator;

use CompanyBundle\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class StepFiveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'translation_domain' => 'tools',
                'empty_data'         => false,
                'label'              => 'mission.view.step_five.person'
            ])
            ->add('firstName', TextType::class, [
                'translation_domain' => 'tools',
                'empty_data'         => false,
                'label'              => false
            ])
            ->add('share', CheckboxType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.form.share'
            ])
            ->add('reference', CheckboxType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.form.reference'
            ])
            ->add('inspectors', Select2EntityType::class, [
                'translation_domain' => 'tools',
                'text_property'      => 'name',
                'remote_route'       => 'inspector_autocomplete',
                'primary_key'        => 'id',
                'allow_add'          => [
                    'enabled'        => true,
                    'new_tag_text'   => ' (NEW)',
                    'new_tag_prefix' => '__',
                    'tag_separators' => '[",", " "]'
                ],
                'multiple'           => true,
                'class'              => 'CompanyBundle\Entity\Inspector',
                'label'              => 'mission.new.form.cac',
            ])
            ->add('sharePitch', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'mission.new.form.share_pitch'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission'
        ]);
    }
}
