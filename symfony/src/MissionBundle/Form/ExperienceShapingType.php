<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use MissionBundle\Entity\ExperienceShaping;
use Symfony\Component\Form\AbstractType;

class ExperienceShapingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('workExperience')
            ->add('companySize', EntityType::class, [
                'class'                     => 'MissionBundle:CompanySize',
                'property'                  => 'name',
                'multiple'                  => true,
                'required'                  => true,
                'expanded'                  => true,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools',
                'constraints'               => new Count([
                    'min' => 1,
                    'minMessage' => 'user.companysize.min',
                ])
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
                    'min' => 1,
                    'minMessage' => 'user.continent.min',
                ])
            ])
            ->add('cumuledMonth', IntegerType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.cumuledmonth',
                'required'           => true,
                'attr'               => [
                    'min' => 1
                ]
            ])
            ->add('dailyFees', IntegerType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.dailyfees',
                'required'           => true,
                'attr'               => [
                    'min' => 1
                ]
            ])
            ->add('peremption', CheckboxType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.peremption',
                'required'           => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExperienceShaping::class,
        ]);
    }
}
