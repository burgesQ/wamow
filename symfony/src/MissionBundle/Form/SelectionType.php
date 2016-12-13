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

class SelectionType extends AbstractType
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
            ->add('team', 'entity', array(
                'class' => 'TeamBundle:Team',
                'property' => 'id',
                'query_builder' => function (TeamRepository $entityRepository) use ($missionId, $step) {
                    return $entityRepository->teamInForm($missionId, $step);
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                ))
            ->add('save', 'submit', array(
                'label' => 'mission.selection.saveButton',
                'translation_domain' => 'MissionBundle'
                ))
            ->add('delete', 'submit', array(
                'label' => 'mission.selection.saveButton4',
                'translation_domain' => 'MissionBundle'
                ))
            ;
    }
}
