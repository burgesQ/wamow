<?php

namespace MissionBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function certificationsAutocompleteAction(Request $request)
    {
        $paginator = $this->getDoctrine()->getRepository('MissionBundle:Certification')
            ->findAutocompleteCertification(
                $request->get('q'),
                $request->get('page'),
                $request->get('page_limit'))
        ;
        $data      = ['results' => []];
        foreach ($paginator as $certification) {
            $data['results'][] = [
                'id'   => $certification->getId(),
                'text' => $certification->__toString()
            ];
        }
        $data['more'] = $paginator->count() < $request->get('page_limit') ? false : true;

        return new JsonResponse($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addToShortlistAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        }

        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        // Get Check User
        /** @var \UserBundle\Entity\User $user */
        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        }

        // Get Check UserMission
        $id              = $request->request->get('id');
        /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $id]))
            || $userMission->getStatus() !== UserMission::ONGOING) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        }

        // Get Check Mission And Step
        /** @var \MissionBundle\Entity\Mission $mission */
        if (($mission = $userMission->getMission()) === null
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Count UserMission By the Step
        if (($step = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission' => $mission, 'status'  => 1]))
            && ($nStep = $em->getRepository('MissionBundle:Step')->findOneby([
                'mission'  => $mission, 'position' => $step->getPosition() + 1]))
            && count($userMissionRepo->findAllAtLeastThan($mission, UserMission::SHORTLIST)) < $nStep->getNbMaxUser()) {
            $userMission->setStatus(UserMission::SHORTLIST);
            $em->flush();

            return new JsonResponse(['data' => json_encode('Ok')]);
        }
        throw new NotFoundHttpException($trans->trans('error.mission.not_enough', [], 'tools'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeFromShortlistAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        }

        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        // Get Check User
        /** @var \UserBundle\Entity\User $user */
        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        } elseif (!$request->request->has('id')) {
            throw new \HttpHeaderException('Missing id parameters');
        }

        // Get Check UserMission
        $id              = $request->request->get('id');
        /** @var \MissionBundle\Repository\UserMissionRepository $userMissionRepo */
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $id]))
            || $userMission->getStatus() !== UserMission::SHORTLIST) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', ['%id' => $id], 'tools'));
        }

        // Get Check Mission And Step
        /** @var \MissionBundle\Entity\Mission $mission */
        if (($mission = $userMission->getMission()) === null
            || $mission->getStatus() !== Mission::PUBLISHED
            || $user->getCompany() !== $mission->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.mission.not_found', [], 'tools'));
        }

        // Count UserMission By the Step
        if (($step = $em->getRepository('MissionBundle:Step')->findOneby([
            'mission' => $mission, 'status'  => 1])) && $step->getPosition() !== 1) {
            throw new NotFoundHttpException($trans->trans('error.action.failed', [], 'tools'));
        }

        $userMission->setStatus(UserMission::ONGOING);
        $em->flush();

        return new JsonResponse(['data' => json_encode('Ok')]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \HttpHeaderException
     */
    public function refuseUserMissionAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        }

        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        // Get Check User
        /** @var \UserBundle\Entity\User $user */
        if (($user = $this->getUser()) === null) {
            throw new NotFoundHttpException($trans->trans('error.logged', [], 'tools'));
        } elseif (!$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            throw new NotFoundHttpException($trans->trans('error.forbidden', [], 'tools'));
        } elseif (!$request->request->has('id')) {
            throw new \HttpHeaderException('Missing id parameters');
        }

        $id              = $request->request->get('id');
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $id]))) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        }

        $userMission->setStatus(UserMission::DISMISS);
        $em->flush();

        return new JsonResponse(['data' => json_encode('Ok')]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \HttpHeaderException
     */
    public function saveNoteAction(Request $request)
    {
        $trans = $this->get('translator');
        $em    = $this->getDoctrine()->getManager();

        if (!$request->request->has('id')) {
            throw new \HttpHeaderException('Missing id parameters');
        }

        // Get Check UserMission
        $id              = $request->request->get('id');
        $userMissionRepo = $em->getRepository('MissionBundle:UserMission');

        /** @var UserMission $userMission */
        if (!($userMission = $userMissionRepo->findOneBy(['id' => $id]))
        || $this->getUser()->getCompany() !== $userMission->getMission()->getCompany()) {
            throw new NotFoundHttpException($trans->trans('error.user_mission.not_found', [], 'tools'));
        }

        $userMission->setNote($request->request->get('value'));
        $this->getDoctrine()->getManager()->flush();

        return new Response($userMission->getNote(), 200);
    }
}
