<?php

namespace TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use InboxBundle\Form\ThreadType;

class ThreadTeamType extends AbstractType
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

            ->add('threads', 'collection',
                array(
                    'type' => new ThreadType(),
                )
            )
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TeamBundle\Entity\Team'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'teambundle_team';
    }

}
