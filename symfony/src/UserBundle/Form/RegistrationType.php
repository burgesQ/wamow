<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('confidentiality');
        $builder->add('status');
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('gender');
        $builder->add('birthdate');
        $builder->add('dailyFees');
        $builder->add('address');
        $builder->add('zipcode');
        $builder->add('city');
        $builder->add('state');
        $builder->add('country');
        $builder->add('phone');
        $builder->add('image');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getStatus()
    {
        return $this->getBlockPrefix();
    }

    public function getConfidentiality()
    {
        return $this->getBlockPrefix();
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

    public function getAddress()
    {
        return $this->getBlockPrefix();
    }

    public function getZipcode()
    {
        return $this->getBlockPrefix();
    }

    public function getCity()
    {
        return $this->getBlockPrefix();
    }

    public function getState()
    {
        return $this->getBlockPrefix();
    }

    public function getCountry()
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
