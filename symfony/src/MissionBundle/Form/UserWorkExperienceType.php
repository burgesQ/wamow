<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use MissionBundle\Entity\UserWorkExperience;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class UserWorkExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workExperience', EntityType::class, [
                'class'         => 'MissionBundle:WorkExperience',
                'attr'          => [
                    'type'   => ChoiceType::class,
                    'hidden' => true
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },

                'property'                  => 'name',
                'multiple'                  => false,
                'required'                  => true,
                'expanded'                  => false,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools'
            ])
            ->add('companySizes', EntityType::class, [
                'class'                     => 'MissionBundle:CompanySize',
                'property'                  => 'name',
                'multiple'                  => true,
                'required'                  => true,
                'expanded'                  => true,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools',
                'query_builder'             => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'constraints'               => new Count([
                    'min'        => 1,
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
                    'min'        => 1,
                    'minMessage' => 'user.continent.min',
                ])
            ])
            ->add('cumuledMonth', RangeType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.cumuledmonth',
                'required'           => true,
                'attr'               => [
                    'min' => 1
                ]
            ])
            ->add('dailyFees', RangeType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.dailyfees',
                'required'           => true,
                'attr'               => [
                    'min' => 1,
                    'max' => 10000,
                ]
            ])
            ->add('peremption', CheckboxType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.peremption',
                'required'           => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserWorkExperience::class,
        ]);
    }
}
