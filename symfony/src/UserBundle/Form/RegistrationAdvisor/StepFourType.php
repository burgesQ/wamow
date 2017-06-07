<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use MissionBundle\Form\UserWorkExperienceType;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;

class StepFourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')
            ->add('workExperience', EntityType::class, [
                'class'                     => 'MissionBundle:WorkExperience',
                'query_builder'             => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.name != :create')
                        ->setParameter('create', 'workexperience.create')
                        ->orderBy('u.id', 'ASC');
                },
                'property'                  => 'name',
                'multiple'                  => true,
                'mapped'                    => false,
                'expanded'                  => true,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools',
                'constraints'               => new Count([
                    'min'        => 1,
                    'minMessage' => 'user.valuableworkexperience.min',
                    'max'        => 10,
                    'maxMessage' => 'user.valuableworkexperience.max',
                ])
            ])
            ->add('userWorkExperiences', CollectionType::class, [
                'type'               => new UserWorkExperienceType(),
                'allow_add'          => true,
                'allow_delete'       => true,
                'allow_extra_fields' => true
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.nextbutton'
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.four.backbutton',
                'validation_groups'  => false,
            ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
