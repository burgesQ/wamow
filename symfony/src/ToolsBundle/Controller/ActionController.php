<?php

namespace ToolsBundle\Controller;

use MissionBundle\Entity\UserMission;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function languagesAutocompleteAction(Request $request)
    {
        $trans     = $this->get('translator');
        $languages = $this->getDoctrine()
            ->getRepository('ToolsBundle:Language')->findAll();

        $data = ['results' => []];
        /** @var \ToolsBundle\Entity\Language $language */
        foreach ($languages as $language) {
            if (stristr($trans->trans($language->__toString(), [], 'tools'),
                    $request->get('q')) != false) {
                $data['results'][] = [
                    'id'   => $language->getId(),
                    'text' => $trans->trans($language->__toString(), [], 'tools')
                ];
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public
    function downloadProposalAction($id)
    {
        if (!($user = $this->getUser())) {
            throw $this->createNotFoundException('Not logged');
        } elseif ((!($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')
            ->findOneBy(['id' => $id])))) {
            throw $this->createNotFoundException('No such userMission');
        } elseif ($userMission->getStatus() < UserMission::SHORTLIST
            || ($user !== $userMission->getUser()
                && $user->getCompany() !== $userMission->getMission()->getCompany())) {
            throw $this->createNotFoundException('No right');
        }
        /** @var \ToolsBundle\Entity\Proposal $proposal */
        $proposal = $userMission->getThread()->getProposals()->last();

        $response = new BinaryFileResponse($proposal->getWebPath());
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'proposale.pdf');

        return $response;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public
    function downloadProposalAdvisorAction($id)
    {
        if (!($user = $this->getUser())) {
            throw $this->createNotFoundException('Not logged');
        } elseif ((!($mission = $this->getDoctrine()->getRepository('MissionBundle:Mission')
                ->findOneBy(['id' => $id]))) ||
            !($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')->findOneBy([
                'mission' => $mission,
                'user'    => $user
            ]))) {
            throw $this->createNotFoundException('No such userMission');
        } elseif ($userMission->getStatus() < UserMission::SHORTLIST) {
            throw $this->createNotFoundException('No right');
        }
        /** @var \ToolsBundle\Entity\Proposal $proposal */
        $proposal = $userMission->getMission()->getProposals()->last();

        $response = new BinaryFileResponse($proposal->getWebPath());
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'proposale.pdf');

        return $response;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public
    function downloadResumeAction($id)
    {
        if (!($user = $this->getUser()) || !$this->isGranted('ROLE_CONTRACTOR')) {
            throw $this->createNotFoundException('Not logged');
        } elseif ((!($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')
            ->findOneBy(['id' => $id])))) {
            throw $this->createNotFoundException('No such userMission');
        } elseif ($userMission->getStatus() < UserMission::SHORTLIST
            || $userMission->getMission()->getCompany() !== $user->getCompany()
            || !($resume = $userMission->getUser()->getResumes()->last())) {
            throw $this->createNotFoundException('No right');
        }

        /** @var \ToolsBundle\Entity\UploadResume $resume */
        $response = new BinaryFileResponse($resume->getWebPath());
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'proposale.pdf');

        return $response;
    }
}
