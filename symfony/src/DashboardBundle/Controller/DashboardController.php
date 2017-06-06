<?php

namespace DashboardBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;

class DashboardController extends Controller
{
    public function dashboardAction()
    {
        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();
        $user  = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            if (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
                return $this->redirectToRoute($url);
            }

            /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
            $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
            $news            = $userMissionRepo->findAllNewMission($user);
            $wips            = $userMissionRepo->getMyMissions($user->getId());
            $closes          = $userMissionRepo->getMyOldMissions($user->getId());

            return $this->render('DashboardBundle:Expert:dashboard.html.twig', [
                'user'   => $user,
                'news'   => $news,
                'wips'   => $wips,
                'closes' => $closes
            ]);
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            $missionRepo = $em->getRepository('MissionBundle:Mission');
            $missions    = $missionRepo->getContractorMissions($user->getId(), $user->getCompany()->getId(), Mission::DRAFT);
            $drafts      = $missionRepo->findBy([
                    'contact' => $user,
                    'company' => $user->getCompany(),
                    'status'  => Mission::DRAFT
                ]);

            return $this->render('DashboardBundle:Seeker:dashboard.html.twig', [
                'user'     => $user,
                'missions' => $missions,
                'drafts'   => $drafts
            ]);
        }
        throw new NotFoundHttpException($trans->trans('dashboard.error.logged', [], 'tools'));
    }
}
