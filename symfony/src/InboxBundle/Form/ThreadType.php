<?php

namespace InboxBundle\Form;

use FOS\MessageBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('mission')
            ->remove('teamCreator')

            ->add('reply', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextareaType'),
                array(
                    'label' => 'body',
                    'translation_domain' => 'FOSMessageBundle',
                )
            )

            ->add('submit', 'submit')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InboxBundle\Entity\Thread'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'inboxbundle_thread';
    }


}
