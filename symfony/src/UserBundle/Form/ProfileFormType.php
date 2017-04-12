<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use ToolsBundle\Form\PhoneNumberType;
use UserBundle\Entity\User;

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
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.firstname',
                ]
            )
            ->add('lastName',  'text',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.lastname',
                ]
            )
            ->add('gender', ChoiceType::class,
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.gender.gender',
                    'choices' =>
                        [
                            '0' => 'form.gender.female',
                            '1' => 'form.gender.male',
                            '2' => 'form.gender.other',
                        ],
                    'required'    => false,
                    'placeholder' => 'form.gender.choose',
                    'empty_data'  => null
                ]
            )
            ->add('email', 'email',
                [
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle'
                ]
            )
            ->add('emergencyEmail', 'email' ,
                [
                    'label' => 'form.emergencyEmail',
                    'required' => false,
                    'translation_domain' => 'FOSUserBundle'
                ]
            )
            ->add('birthdate', 'birthday',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.birthdate.birthdate',
                    'widget' => 'choice',
                    'required'    => false,
                    'placeholder' =>
                        [
                            'month' => 'form.birthdate.month',
                            'day' => 'form.birthdate.day',
                            'year' => 'form.birthdate.year',
                        ],
                    'format' => 'MMMMdy',
                    'pattern' => "{{ month }}/{{ day }}/{{ year }}",
                    'years' => range(date('Y') - 12, date('Y') - 110),
                ]
            )
            ->add('dailyFeesMin', null,
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.dailyfees.min'
                ]
            )
            ->add('dailyFeesMax', null,
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.dailyfees.max'
                ]
            )
            ->add('country',  'country',
                [
                    'required' => true,
                    'label'=>'form.address.country',
                    'translation_domain' => 'tools',
                    'placeholder' => 'form.address.choosecountry'
                ]
            )
            ->add('phone', new PhoneNumberType(),
                [
                    'required' => false
                ]
            )
            ->add('newsletter',  'checkbox',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.newsletter',
                    'required' => false,
                ]
            )
            ->add('userResume', 'textarea',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.userResume',
                    'required' => false,
                ]
            )
            ->add('missionKind',   'entity',
                [
                    'class' => 'MissionBundle:MissionKind',
                    'property' => 'name',
                    'multiple' => true,
                    'required'    => false,
                    'label' => 'mission.new.form.missionKind',
                    'placeholder' => 'mission.new.form.chooseMissionKind',
                    'translation_domain' => 'MissionBundle',
                    'choice_translation_domain' => 'MissionBundle',
                ]
            )
            ->add('businessPractice',   'entity',
                [
                      'class' => 'MissionBundle:BusinessPractice',
                      'property' => 'name',
                      'multiple' => true,
                      'required'    => false,
                      'label' => 'mission.new.form.businessPractice',
                      'placeholder' => 'mission.new.form.businessPractice',
                      'translation_domain' => 'MissionBundle',
                      'choice_translation_domain' => 'MissionBundle',
                ]
            )
            ->add('languages',   'entity',
                [
                    'class' => 'ToolsBundle:Language',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'required'    => false,
                    'label' => 'form.languages',
                    'translation_domain' => 'FOSUserBundle',
                    'choice_translation_domain' => 'tools',
                ]
            )
            ->add('professionalExpertise',   'entity',
                [
                    'class' => 'MissionBundle:ProfessionalExpertise',
                    'property' => 'name',
                    'multiple' => true,
                    'required'    => false,
                    'placeholder' => 'mission.new.form.chooseExpertise',
                    'label' => 'mission.new.form.professionalExpertise',
                    'translation_domain' => 'MissionBundle',
                    'choice_translation_domain' => 'MissionBundle',
                    'required'    => false
                ]
            )
            ->add('nbLoad', null,
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.nbLoad'
                ]
            )
            ->add('readReport', 'checkbox',
                [
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.readReport',
                    'required'    => false
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => User::class,
                'intention'  => 'profile',
        ]);
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
