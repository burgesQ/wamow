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
use MissionBundle\Form\MissionGenerator\StepThreeFormType;

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

    public function getNbMatchAction(Request $request, $missionId)
    {
        $em           = $this->getDoctrine()->getManager();
        $mission      = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId]);
        $user         = $this->getUser();
        $trans = $this->get('translator');

        // NOTE : Duplicate stepThreeAction

        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'contact' => $user]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }

        $stepMission = $newMission->getStatusGenerator();
        if ($stepMission !== Mission::STEP_TWO && $stepMission !== Mission::STEP_THREE) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }

        $newMission->setOnDraft(true);
        $arrayForm = [];

        if ($stepMission === Mission::STEP_THREE) {
            $arrayForm['stepFour']  = 'display: none;';
            $arrayForm['labelBack'] = 'mission.new.form.back_resume';
            $arrayForm['labelNext'] = 'mission.new.form.save_mod';
        }
        /** @var \Symfony\Component\Form\Form $formStepThree */
        $formStepThree = $this->createForm(new StepThreeFormType(), $newMission, $arrayForm)->setData($newMission);

        if ($formStepThree->handleRequest($request)->isSubmitted() && $formStepThree->isValid()) {
            // NOTE : Flush or not ?
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                "matches" => count($this->getDoctrine()->getManager()->getRepository("MissionBundle:Mission")
                    ->findUsersByMission($mission))
            ]);
        }

        throw $this->createNotFoundException($trans->trans('mission.error.forbiddenAccess', [], 'MissionBundle'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $missionId
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getWorkExpAction(Request $request, $missionId)
    {
        $em           = $this->getDoctrine()->getManager();
        $mission      = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId]);
        $user         = $this->getUser();
        $trans = $this->get('translator');

        // NOTE : Duplicate stepThreeAction

        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'contact' => $user]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }

        $stepMission = $newMission->getStatusGenerator();
        if ($stepMission !== Mission::STEP_TWO && $stepMission !== Mission::STEP_THREE) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }

        $newMission->setOnDraft(true);
        $arrayForm = [];

        if ($stepMission === Mission::STEP_THREE) {
            $arrayForm['stepFour']  = 'display: none;';
            $arrayForm['labelBack'] = 'mission.new.form.back_resume';
            $arrayForm['labelNext'] = 'mission.new.form.save_mod';
        }

        /** @var \Symfony\Component\Form\Form $formStepThree */
        $formStepThree = $this->createForm(new StepThreeFormType(), $newMission, $arrayForm)->setData($newMission);

        if ($formStepThree->handleRequest($request)->isSubmitted() && $formStepThree->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $workExp = $this->getDoctrine()->getRepository("MissionBundle:WorkExperience")
                ->findWorkExp($mission);
            $array = [];
            foreach ($workExp as $oneWork) {
                $array += [$oneWork->getId() => $trans->trans($oneWork->getName(), [], "tools")];
            }
            return new JsonResponse([
                "work_experiences" => $array
            ]);
        }

        throw $this->createNotFoundException($trans->trans('mission.error.forbiddenAccess', [], 'MissionBundle'));
    }



}
