<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class MessageMissionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'translation_domain' => 'tools',
                'attr'               => [
                    'placeholder' => 'inbox.form.placeholder.empty'
                ],
                'required'           => true
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'inbox.form.btn_submit_empty_interested'
            ])
        ;
    }
}