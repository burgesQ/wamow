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
        $repository = $em->getRepository('MissionBundle:Mission');
        $userId = $this->getUser()->getId();
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            $missions = $repository->getExpertMissionsAvailables();
            return $this->render('DashboardBundle:Expert:index.html.twig', array(
                'missions' => $missions
                ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $missions = $repository->getSeekerMissions($userId);
            return $this->render('DashboardBundle:Seeker:index.html.twig', array(
                'missions' => $missions
                ));
        }
        throw new NotFoundHttpException("You are not logged.");
    }
}
