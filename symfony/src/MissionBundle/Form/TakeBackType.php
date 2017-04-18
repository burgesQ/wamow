<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MissionBundle\Entity\Mission;
use MissionBundle\Repository\MissionRepository;
use MissionBundle\Repository\UserMissionRepository;

class TakeBackType extends AbstractType
{
    private $missionId;
    private $step;

    public function __construct($missionId, $step)
    {
        $this->missionId = $missionId;
        $this->step = $step;
    }

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $missionId = $this->missionId;
        $step = $this->step;
        $builder
            ->add('userMission', EntityType::class, array(
                'class' => 'MissionBundle:UserMission',
                'label' => false,
                'property' => 'id',
                'query_builder' => function (UserMissionRepository $entityRepository) use ($missionId, $step) {
                    return $entityRepository->getAvailablesUsers($missionId, $step, true);
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ))
            ->add('save', 'submit', array(
                'label' => 'mission.selection.saveButton2',
                'translation_domain' => 'MissionBundle'
            ));
    }
}
