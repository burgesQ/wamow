<?php
namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use ToolsBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DashboardController extends Controller
{
    public function missionDisplayAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryMission = $em->getRepository('MissionBundle:Mission');
        $repositoryTeam = $em->getRepository('TeamBundle:Team');
        $userId = $this->getUser()->getId();
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            $service = $this->container->get('mission');
            $missions = $repositoryMission->expertMissionsAvailables();
            $currentsMissions = $repositoryTeam->getTeamsByUserId($userId);
            $availablesMissions = $service->organiseMissions($missions, $currentsMissions);
            return $this->render('DashboardBundle:Expert:index.html.twig', array(
                'availablesMissions' => $availablesMissions,
                'currentsMissions'   => $currentsMissions,
                ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $listMission = $repositoryTeam->getTeamsByUserId($userId);
            return $this->render('DashboardBundle:Seeker:index.html.twig', array(
                'listMission' => $listMission
                ));
        }
        throw new NotFoundHttpException("You are not logged.");
    }
}
