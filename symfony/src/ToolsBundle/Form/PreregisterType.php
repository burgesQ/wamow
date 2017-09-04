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
            ->add('email', EmailType::class, [
                'required'           => true,
                'translation_domain' => 'tools',
                'label'              => 'home.contractor.preregister.email'
            ])
            ->add('firstname', TextType::class, [
                'required'           => true,
                'translation_domain' => 'tools',
                'label'              => 'home.contractor.preregister.firstname'
            ])
            ->add('lastname', TextType::class, [
                'required'           => true,
                'translation_domain' => 'tools',
                'label'              => 'home.contractor.preregister.lastname'
            ])
            ->add('company', TextType::class, [
                'required'           => true,
                'translation_domain' => 'tools',
                'label'              => 'home.contractor.preregister.company'
            ])
            ->add('country', CountryType::class, [
                'required'           => true,
                'translation_domain' => 'tools',
                'label'              => 'home.contractor.preregister.country'
            ])
            ->add('phone', new PhoneNumberType(), [
                'required' => true,
            ])
            ->add('comment', TextType::class, [
                'required'           => false,
                'label'              => 'home.contractor.preregister.comment',
                'translation_domain' => 'tools',
            ])
            ->add('submit', SubmitType::class, [
                'label'              => 'home.contractor.preregister.submit',
                'translation_domain' => 'tools',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ToolsBundle\Entity\Preregister'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toolsbundle_preregister';
    }


}
