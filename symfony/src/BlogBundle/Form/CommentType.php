<?php

namespace BlogBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface,
    Extension\Core\Type\TextType,
    Extension\Core\Type\SubmitType,
    Extension\Core\Type\TextareaType
};

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('postDate')
            ->remove('article')

            ->add('emailAuthor', TextType::class,
                [
                    'translation_domain' => 'BlogBundle',
                    'label' => 'view.comment.author_email',
                    'required' => true,
                ]
            )
            ->add('firstNameAuthor', TextType::class,
                [
                    'translation_domain' => 'BlogBundle',
                    'label' => 'view.comment.author_first_name',
                    'required' => true,
                ]
            )
            ->add('lastNameAuthor', TextType::class,
                [
                    'translation_domain' => 'BlogBundle',
                    'label' => 'view.comment.author_last_name',
                    'required' => true,
                ]
            )
            ->add('content', TextareaType::class,
                [
                    'translation_domain' => 'BlogBundle',
                    'label' => 'view.comment.content',
                    'required' => true,
                ]
            )
            ->add('post', SubmitType::class,
                [
                    'translation_domain' => 'BlogBundle',
                    'label' => 'view.comment.post',
                ]
            )
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlogBundle\Entity\Comment'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_comment';
    }
}
