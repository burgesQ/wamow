<?php

namespace UserBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use ToolsBundle\Form\AddressType;
use ToolsBundle\Form\PhoneNumberType;
use ToolsBundle\Form\UploadType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->add('firstName',  'text', array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.firstname',
            ))
            ->add('lastName',  'text', array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.lastname',
            ))
            ->add('gender', ChoiceType::class, array(
                'translation_domain' => 'FOSUserBundle',
                'choices' => array(
                    '0' => 'form.gender.female',
                    '1' => 'form.gender.male',
                    '2' => 'form.gender.other',
                ),
                'required'    => false,
                'placeholder' => 'form.gender.choose',
                'empty_data'  => null
            ))
            ->add('birthdate', 'birthday', array(
                'translation_domain' => 'FOSUserBundle',
                'widget' => 'choice',
                'required'    => false,
                'placeholder' => array(
                    'month' => 'form.birthdate.month',
                    'day' => 'form.birthdate.day',
                    'year' => 'form.birthdate.year',
                ),
                'format' => 'MMMMdy',
                'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                'years' => range(date('Y') - 12, date('Y') - 110),
            ))
            ->add('dailyFeesMin', null, array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.dailyfees.min'
            ))
            ->add('dailyFeesMax', null, array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.dailyfees.max'
            ))
            ->add('address', new AddressType(), array(
                'required' => true
            ))
            ->add('phone', new PhoneNumberType(), array(
                'required' => false
            ))
            ->add('image', new UploadType(), array(
                'required' => false,
            ))
            ->add('resume', new UploadType(),array(
                'required' => false,
            ))
            ->add('newsletter',  'checkbox', array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.newsletter',
                'required' => false,
            ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    public function getFirstName()
    {
        return $this->getBlockPrefix();
    }

    public function getLastName()
    {
        return $this->getBlockPrefix();
    }

    public function getGender()
    {
        return $this->getBlockPrefix();
    }

    public function getBirthdate()
    {
        return $this->getBlockPrefix();
    }

    public function getDailyFeesMin()
    {
        return $this->getBlockPrefix();
    }

    public function getDailyFeesMax()
    {
        return $this->getBlockPrefix();
    }

    public function getImage()
    {
        return $this->getBlockPrefix();
    }

    public function getNewsletter()
    {
        return $this->getBlockPrefix();
    }
}
