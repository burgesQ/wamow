<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class StepThreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')

            ->add('missionKind', EntityType::class, [
                    'class'                     => 'MissionBundle:MissionKind',
                    'property'                  => 'name',
                    'multiple'                  => true,
                    'expanded'                  => true,
                    'label'                     => false,
                    'translation_domain'        => 'tools',
                    'choice_translation_domain' => 'tools',
                    'constraints'               => new Count([
                        'min' => 1,
                        'minMessage' => 'user.kindfomission.min',
                    ])
            ])
            ->add('submit', SubmitType::class, [
                    'translation_domain' => 'tools',
                    'label'              => 'registration.advisor.three.nextbutton',
            ])
        ;
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
