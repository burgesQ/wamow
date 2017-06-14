<?php

namespace ToolsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use ToolsBundle\Form\PhoneNumberType;

class PreregisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                array(
                    'required' => true,
                    'translation_domain' => 'tools',
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.email',
                    )
            ))
            ->add('firstname', TextType::class,
                array(
                    'required' => true,
                    'translation_domain' => 'tools',
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.firstname',
                    )
            ))
            ->add('lastname', TextType::class,
                array(
                    'required' => true,
                    'translation_domain' => 'tools',
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.lastname',
                    )
            ))
            ->add('company', TextType::class,
                array(
                    'required' => true,
                    'translation_domain' => 'tools',
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.company',
                    )
            ))
            ->add('country', CountryType::class,
                array(
                    'required' => true,
                    'label'=> false,
                    'translation_domain' => 'tools',
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.country',
                    )
            ))
            ->add('phone', new PhoneNumberType(),
                array(
                    'required' => true,
            ))
            ->add('comment', TextareaType::class,
                array(
                    'required' => false,
                    'label'=> false,
                    'translation_domain' => 'tools',
                    'attr' => array(
                        'placeholder' => 'home.contractor.preregister.comment',
                    )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'home.contractor.preregister.submit',
                'translation_domain' => 'tools',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ToolsBundle\Entity\Preregister'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toolsbundle_preregister';
    }


}
