<?php

namespace UserBundle\Form\RegistrationAdvisor;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use ToolsBundle\Form\UploadResumeType;

class MergedFormRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new RegistrationType())
            ->add('resume', new UploadResumeType(), [
                'translation_domain'       => 'tools',
                'required'                 => false,
                'label'                    => 'registration.advisor.zero.resume',
            ])
            ->add('save', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'registration.advisor.zero.nextbutton'
            ])
        ;
    }
}
