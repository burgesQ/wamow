<?php

namespace MissionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use TeamBundle\Entity\Team;
use MissionBundle\Entity\Mission;
use MissionBundle\Repository\MissionRepository;
use TeamBundle\Repository\TeamRepository;

class TakeBackType extends AbstractType
{
    private $missionId;

    public function __construct($missionId)
    {
        $this->missionId = $missionId;
    }

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $missionId = $this->missionId;
        $builder
            ->add('team', 'entity', array(
                'class' => 'TeamBundle:Team',
                'property' => 'id',
                'query_builder' => function (TeamRepository $entityRepository) use ($missionId) {
                    return $entityRepository->takeBackTeams($missionId);
                },
                'expanded' => true
                ))
            ->add('save', 'submit', array(
                'label' => 'mission.selection.saveButton2',
                'translation_domain' => 'MissionBundle'
                ))
            ;
    }
}
