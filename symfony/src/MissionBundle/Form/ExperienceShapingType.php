<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use MissionBundle\Entity\ExperienceShaping;

class ExperienceShapingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('workTitle')
            ->add('smallCompany', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.smallCompany',
                      'required' => false,
                      'data_class' => null,
                  ]
            )
            ->add('mediumCompany', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.mediumCompany',
                      'required' => false,
                  ]
            )
            ->add('largeCompany', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.largeCompany',
                      'required' => false,
                  ]
            )
            ->add('southAmerica', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.southAmerica',
                      'required' => false,
                  ]
            )
            ->add('northAmerica', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.northAmerica',
                      'required' => false,
                  ]
            )
            ->add('asia', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.asia',
                      'required' => false,
                  ]
            )
            ->add('emea', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.emea',
                      'required' => false,
                  ]
            )
            ->add('cumuledMonth', 'integer',
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.cumuledMonth',
                      'required' => false,
                  ]
            )
            ->add('dailyFees', 'integer',
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.dailyFees',
                      'required' => false,
                  ]
            )
            ->add('peremption', CheckboxType::class,
                  [
                      'translation_domain' => 'ExperienceShaping',
                      'label' => 'form.label.peremption',
                      'required' => false,
                  ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ExperienceShaping::class,
            ]
        );
    }

}
