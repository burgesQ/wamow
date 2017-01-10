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
    private $position;

    public function __construct($missionId, $position)
    {
        $this->missionId = $missionId;
        $this->position = $position;
    }

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $missionId = $this->missionId;
        $position = $this->position;
        $builder
            ->add('team', EntityType::class, array(
                'class' => 'TeamBundle:Team',
                'property' => 'id',
                'query_builder' => function (TeamRepository $entityRepository) use ($missionId, $position) {
                    return $entityRepository->getTeamsForForm($missionId, $position);
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
