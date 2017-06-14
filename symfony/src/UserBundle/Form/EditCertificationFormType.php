<?php

namespace UserBundle\Form;

use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use UserBundle\Entity\User;

class EditCertificationFormType extends AbstractType
{
    /**
     * A lil form just to display/edit the certifications of a given user
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('save', SubmitType::class, [
                'translation_domain' => 'tools',
                'label'              => 'form.btn.save_certif'
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
