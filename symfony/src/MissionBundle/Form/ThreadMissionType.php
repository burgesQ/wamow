<?php

namespace MissionBundle\Form;

use InboxBundle\Form\ThreadType;
use MissionBundle\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadMissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('creationDate')
            ->remove('updateDate')
            ->remove('role')
            ->remove('status')
            ->remove('mission')
            ->remove('users')

            ->add('threads', 'collection', [
                'type' => new ThreadType(),
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'thread_mission_type';
    }
}
