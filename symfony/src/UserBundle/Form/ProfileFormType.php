<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ToolsBundle\Form\PhoneNumberType;
use ToolsBundle\Form\AddressType;
use ToolsBundle\Form\UploadType;

class ProfileFormType extends AbstractType
{
    private $class;

    /**
    * @param string $class The User class name
    */
    public function __construct($class)
    {
      $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('current_password')
            ->add('firstName',  'text',
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.firstname',
                  ))

            ->add('lastName',  'text',
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.lastname',
                  ))

            ->add('gender', ChoiceType::class,
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.gender.gender',
                      'choices' =>
                      array(
                          '0' => 'form.gender.female',
                          '1' => 'form.gender.male',
                          '2' => 'form.gender.other',
                      ),
                      'required'    => false,
                      'placeholder' => 'form.gender.choose',
                      'empty_data'  => null
                  ))

            ->add('email', 'email',
                  array('label' => 'form.email',
                        'translation_domain' => 'FOSUserBundle'
                  ))

            ->add('birthdate', 'birthday',
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.birthdate.birthdate',
                      'widget' => 'choice',
                      'required'    => false,
                      'placeholder' =>
                      array(
                          'month' => 'form.birthdate.month',
                          'day' => 'form.birthdate.day',
                          'year' => 'form.birthdate.year',
                      ),
                      'format' => 'MMMMdy',
                      'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                      'years' => range(date('Y') - 12, date('Y') - 110),
                  ))

            ->add('dailyFeesMin', null,
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.dailyfees.min'
                  ))

            ->add('dailyFeesMax', null,
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.dailyfees.max'
                  ))

            ->add('country',  'country',
                  array(
                      'required' => true,
                      'label'=>'form.address.country',
                      'translation_domain' => 'tools',
                      'placeholder' => 'form.address.choosecountry'
                  ))

            ->add('phone', new PhoneNumberType(),
                  array(
                      'required' => false
                  ))

            ->add('newsletter',  'checkbox',
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.newsletter',
                      'required' => false,
                  ))

            ->add('userResume', 'textarea',
                  array(
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.userResume',
                      'required' => false
                  ))

            ->add('missionKind',   'entity',
                  array(
                      'class' => 'MissionBundle:MissionKind',
                      'property' => 'name',
                      'multiple' => true,
                      'placeholder' => 'mission.new.form.chooseMissionKind',
                      'label' => 'mission.new.form.missionKind',
                      'translation_domain' => 'MissionBundle',
                      'required'    => false
                  ))

            ->add('businessPractice',   'entity',
                  array(
                      'class' => 'MissionBundle:BusinessPractice',
                      'property' => 'name',
                      'multiple' => true,
                      'placeholder' => 'mission.new.form.businessPractice',
                      'label' => 'mission.new.form.businessPractice',
                      'translation_domain' => 'MissionBundle',
                      'required'    => false
                  ))

            ->add('languages',   'entity',
                  array(
                      'class' => 'ToolsBundle:Language',
                      'property' => 'name',
                      'multiple' => true,
                      'expanded' => true,
                      'translation_domain' => 'FOSUserBundle',
                      'label' => 'form.languages',
                      'required'    => false
                  ))

            ->add('professionalExpertise',   'entity',
                  array(
                      'class' => 'MissionBundle:ProfessionalExpertise',
                      'property' => 'name',
                      'multiple' => true,
                      'placeholder' => 'mission.new.form.chooseExpertise',
                      'translation_domain' => 'MissionBundle',
                      'label' => 'mission.new.form.professionalExpertise',
                      'required'    => false
                  ))            
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => $this->class,
                'intention'  => 'profile',
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

    public function getNewsletter()
    {
        return $this->getBlockPrefix();
    }
}
