<?php
namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Mission;
use MissionBundle\Entity\UserMission;
use MissionBundle\Form\MissionType;
use ToolsBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DashboardController extends Controller
{
    public function dashboardAction()
    {
        $trans = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR'))
        {
            if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus())))
            {
                return $this->redirectToRoute($url);
            }
            $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
            $news = $userMissionRepo->findBy(array('user' => $user, 'status' => UserMission::NEW));
            $wips = $userMissionRepo->getMyMissions($user->getId());
            $closes = $userMissionRepo->getMyOldMissions($user->getId());
            return $this->render('DashboardBundle:Expert:dashboard.html.twig', array(
                'user'   => $user,
                'news'   => $news,
                'wips'   => $wips,
                'closes' => $closes
            ));
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR'))
        {
            $missionRepo = $em->getRepository('MissionBundle:Mission');
            $missions = $missionRepo->getContractorMissions($user->getId(), $user->getCompany()->getId(), Mission::DRAFT);
            $drafts = $missionRepo->findBy(array('contact' => $user, 'company' => $user->getCompany(), 'status' =>
                Mission::DRAFT));
            return $this->render('DashboardBundle:Seeker:dashboard.html.twig', array(
                'missions' => $missions,
                'drafts'   => $drafts
            ));
        }
        throw new NotFoundHttpException($trans->trans('dashboard.error.logged', array(), 'tools'));
    }
}
