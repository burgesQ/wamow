<?php
namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use MissionBundle\Form\StepType;
use ToolsBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DashboardController extends Controller
{
    public function missionDisplayAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('MissionBundle:Mission')
            ;
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            $service = $this->container->get('current.mission');
            $missions = $repository->expertMissionsAvailables();
            $currentsMissions = $repository->myMissions($this->getUser());
            $availablesMissions = $service->remainingMission($missions, $currentsMissions);
            return $this->render('DashboardBundle:Expert:index.html.twig', array(
                'availablesMissions' => $availablesMissions,
                'currentsMissions'   => $currentsMissions,
                ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $user = $this->getUser();
            $iD = $user->getId();
            $listMission = $repository->myMissions($iD);
            return $this->render('DashboardBundle:Seeker:index.html.twig', array(
                'listMission' => $listMission
                ));
        }
        throw new NotFoundHttpException("You are not logged.");
    }
}
