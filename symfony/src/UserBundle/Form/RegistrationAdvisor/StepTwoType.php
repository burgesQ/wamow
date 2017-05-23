<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class StepTwoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->remove('email')
            ->add('professionalExpertise', EntityType::class, [
                'class'                     => 'MissionBundle:ProfessionalExpertise',
                'property'                  => 'name',
                'multiple'                  => true,
                'expanded'                  => true,
                'label'                     => false,
                'translation_domain'        => 'tools',
                'choice_translation_domain' => 'tools',
                'constraints'               => new Count([
                    'min' => 1,
                    'minMessage' => 'user.professionalexpertises.min',
                ])
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.two.nextbutton'
            ])
            ->add('back', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.two.backbutton',
                'validation_groups'  => false,
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
