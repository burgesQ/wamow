<?php

namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use ToolsBundle\Form\PhoneNumberType;
use ToolsBundle\Form\AddressType;
use UserBundle\Entity\User;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->add('email', EmailType::class, [
                'required' => true,
                'label'    => false
            ])
            ->add('emergencyEmail', EmailType::class, [
                'required' => true,
                'label'    => false
            ])
            ->add('phone', PhoneNumberType::class, [
                'required' => true
            ])
            ->add('addresses', CollectionType::class, [
                'allow_delete' => false,
                'entry_type'   => AddressType::class,
                'allow_add'    => false,
                'required'     => true
            ]);

        if (!strcmp($options['role'], 'ROLE_ADVISOR')) {
            $builder
                ->add('languages', Select2EntityType::class, [
                    'translation_domain' => 'tools',
                    'text_property'      => 'name',
                    'remote_route'       => 'languages_autocomplete',
                    'primary_key'        => 'id',
                    'allow_add'          => [
                        'enabled' => false
                    ],
                    'multiple'           => true,
                    'required'           => true,
                    'class'              => 'ToolsBundle\Entity\Language',
                    'label'              => 'mission.new.label.language'
                ])
                ->add('certifications', Select2EntityType::class, [
                    'translation_domain' => 'tools',
                    'text_property'      => 'name',
                    'remote_route'       => 'certifications_autocomplete',
                    'primary_key'        => 'id',
                    'allow_add'          => [
                        'enabled'        => true,
                        'new_tag_text'   => ' (NEW)',
                        'new_tag_prefix' => '__',
                        'tag_separators' => '[""]'
                    ],
                    'multiple'           => true,
                    'class'              => 'MissionBundle\Entity\Certification',
                    'label'              => 'mission.new.form.certification'
                ])
                ->add('siret', TextType::class, [
                    'translation_domain' => 'tools',
                    'required'           => false,
                    'label'              => false,
                    'max_length'         => 35
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role'       => 'ROLE_CONTRACTOR'
        ]);
    }
}
