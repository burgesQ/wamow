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

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('gender', ChoiceType::class, array(
            'choices' => array(
                '0' => 'Female',
                '1' => 'Male',
                '2' => 'I don\'t know',
            ),
            'required'    => false,
            'placeholder' => 'Choose your gender',
            'empty_data'  => null
        ));
        $builder->add('birthdate', 'birthday', array(
            'widget' => 'choice',
            'required'    => false,
            'placeholder' => array(
                'month' => 'Month',
                'day' => 'Day',
                'year' => 'Year',
            ),
            'format' => 'dMMMMy',
            'pattern' => "{{ month }}/{{ day }}/{{ year }}",
            'years' => range(date('Y') - 12, date('Y') - 110),
        ));
        $builder->add('dailyFees');
        $builder->add('address', new AddressType());
        $builder->add('phone');
        $builder->add('image');
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

    public function getDailyFees()
    {
        return $this->getBlockPrefix();
    }

    public function getPhone()
    {
        return $this->getBlockPrefix();
    }

    public function getImage()
    {
        return $this->getBlockPrefix();
    }
}
