<?php

namespace MissionBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Form\MissionGenerator\StepThreeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MissionBundle\Form\MissionGenerator\StepFiveFormType;
use MissionBundle\Form\MissionGenerator\StepFourFormType;
use MissionBundle\Form\MissionGenerator\StepOneFormType;
use MissionBundle\Form\MissionGenerator\StepTwoFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use MissionBundle\Entity\UserMission;
use MissionBundle\Entity\Mission;
use MissionBundle\Entity\Step;

class MissionGeneratorController extends Controller
{
    /**
     * @var array
     */
    private $arrayStep = [
        'mission_new_step_one',
        'mission_new_step_two',
        'mission_new_step_three',
        'mission_new_step_four',
        'mission_new_step_five',
    ];

    /**
     * @param int $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMissionAction($missionId)
    {
        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'company' => $user->getCompany()]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }

        return $this->redirectToRoute($this->arrayStep[$newMission->getStatusGenerator()], [
            'missionId' => $missionId
        ]);
    }

    /**
     * @param Request    $request
     * @param            $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function stepOneAction(Request $request, $missionId)
    {
        // check if user connected and is contractor
        /** @var \UserBundle\Entity\User $user */
        if (!($user = $this->getUser())
            || !$this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }

        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \MissionBundle\Entity\Mission $newMission */

        if ($missionId === null) {
            $newMission = new Mission(3, $user, $user->getCompany());
        } elseif (!($newMission = $missionRepository->findOneby([
            'id'      => $missionId,
            'company' => $user->getCompany()]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }

        $stepMission = $newMission->getStatusGenerator();

        if ($stepMission !== Mission::STEP_ZERO && $stepMission !== Mission::STEP_THREE) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }

        $newMission->setOnDraft(false);
        $arrayForm = [];
        if ($stepMission === Mission::STEP_THREE) {
            $arrayForm['stepFour']  = 'display: none';
            $arrayForm['labelBack'] = 'mission.new.form.back_resume';
            $arrayForm['labelNext'] = 'mission.new.form.save_mod';
        }
        /** @var \Symfony\Component\Form\Form $formStepOne */
        $formStepOne = $this->get('form.factory')
            ->create(StepOneFormType::class, $newMission, $arrayForm)->setData($newMission)
        ;

        if ($formStepOne->handleRequest($request)->isSubmitted() && $formStepOne->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($formStepOne->get('back')->isClicked()) {
                if ($stepMission === Mission::STEP_THREE) {
                    return $this->redirectToRoute('mission_edit', [
                        'missionId' => $newMission->getId()
                    ]);
                }

                return $this->redirectToRoute('dashboard');
            } elseif ($formStepOne->get('forLater')->isClicked()) {
                $newMission->setOnDraft(true);
                $em->persist($newMission);
                $em->flush();

                return $this->redirectToRoute('dashboard');
            }
            // pass next step

            if ($stepMission !== Mission::STEP_THREE) {
                $newMission->setStatusGenerator(Mission::STEP_ONE);
                $em->persist($newMission);
            }
            $em->flush();
            $newMission->setPublicId(md5(uniqid() . $newMission->getId()));
            $em->flush();

            return $this->redirectToRoute('mission_new_step_two', [
                'missionId' => $newMission->getId()
            ]);
        }

        return $this->render('MissionBundle:MissionGenerator:mission_step_one.html.twig', [
            'form' => $formStepOne->createView(),
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param int     $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepTwoAction(Request $request, $missionId)
    {
        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'contact' => $user]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        }
        $stepMission = $newMission->getStatusGenerator();
        if ($stepMission !== Mission::STEP_ONE && $stepMission !== Mission::STEP_THREE) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }

        $newMission->setOnDraft(false);
        $arrayForm = [];

        if ($stepMission === Mission::STEP_THREE) {
            $arrayForm['stepFour']  = 'display: none;';
            $arrayForm['labelBack'] = 'mission.new.form.back_resume';
            $arrayForm['labelNext'] = 'mission.new.form.save_mod';
        }

        /** @var \Symfony\Component\Form\Form $formStepTwo */
        $formStepTwo = $this->get('form.factory')
            ->create(StepTwoFormType::class, $newMission, $arrayForm)->setData($newMission)
        ;

        if ($formStepTwo->handleRequest($request)->isSubmitted() && $formStepTwo->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($formStepTwo->get('back')->isClicked()) {
                if ($stepMission !== Mission::STEP_THREE) {
                    $newMission->setStatusGenerator(Mission::STEP_ZERO);
                    $em->flush();
                }

                return $this->redirectToRoute('mission_edit', [
                    'missionId' => $newMission->getId()
                ]);
            } elseif ($formStepTwo->get('forLater')->isClicked()) {
                $newMission->setOnDraft(true);
                $em->flush();

                return $this->redirectToRoute('dashboard');
            }

            if ($stepMission !== Mission::STEP_THREE) {
                $newMission->setStatusGenerator(Mission::STEP_TWO);
                $em->flush();
            }

            return $this->redirectToRoute('mission_new_step_three', [
                'missionId' => $newMission->getId()
            ]);
        }

        return $this->render('MissionBundle:MissionGenerator:mission_step_two.html.twig', [
            'form' => $formStepTwo->createView(),
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param int     $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepThreeAction(Request $request, $missionId)
    {
        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
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

        $newMission->setOnDraft(false);
        $arrayForm = [];

        if ($stepMission === Mission::STEP_THREE) {
            $arrayForm['stepFour']  = 'display: none;';
            $arrayForm['labelBack'] = 'mission.new.form.back_resume';
            $arrayForm['labelNext'] = 'mission.new.form.save_mod';
        }

        /** @var \Symfony\Component\Form\Form $formStepThree */
        $formStepThree = $this->createForm(new StepThreeFormType(), $newMission, $arrayForm)->setData($newMission);

        if ($formStepThree->handleRequest($request)->isSubmitted() && $formStepThree->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($formStepThree->get('back')->isClicked()) {
                if ($stepMission !== Mission::STEP_THREE) {
                    $newMission->setStatusGenerator(Mission::STEP_ONE);
                    $em->flush();
                }

                return $this->redirectToRoute('mission_edit', [
                    'missionId' => $newMission->getId()
                ]);
            } elseif ($formStepThree->get('forLater')->isClicked()) {
                $newMission->setOnDraft(true);
                $em->flush();

                return $this->redirectToRoute('dashboard');
            }

            $newMission->setStatusGenerator(Mission::STEP_THREE);
            $em->flush();

            return $this->redirectToRoute('mission_new_step_four', [
                'missionId' => $newMission->getId()
            ]);
        }

        return $this->render('MissionBundle:MissionGenerator:mission_step_three.html.twig', [
            'form'      => $formStepThree->createView(),
            'mission'   => $newMission,
            'user'      => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepFourAction(Request $request, $missionId)
    {
        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'contact' => $user]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        } elseif ($newMission->getStatusGenerator() !== Mission::STEP_THREE) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }
        $newMission->setOnDraft(false);
        /** @var \Symfony\Component\Form\Form $formStepFour */
        $formStepFour = $this->get('form.factory')
            ->create(StepFourFormType::class, $newMission)->setData($newMission)
        ;
        if ($formStepFour->handleRequest($request)->isSubmitted() && $formStepFour->isValid()) {
            $em = $this->getDoctrine()->getManager();

            switch ($formStepFour) {
                case $formStepFour->get('back')->isClicked() :
                    return $this->redirectToRoute('mission_new_step_three', [
                        'missionId' => $newMission->getId()
                    ]);
                case $formStepFour->get('forLater')->isClicked() :
                    $newMission->setOnDraft(true);
                    $em->flush();

                    return $this->redirectToRoute('dashboard');

                case $formStepFour->get('edit')->isClicked() :
                    return $this->redirectToRoute('mission_new_step_one', [
                        'missionId' => $newMission->getId()
                    ]);
                case $formStepFour->get('edit_1')->isClicked() :
                    $newMission->setStatusGenerator(Mission::STEP_ONE);

                    return $this->redirectToRoute('mission_new_step_two', [
                        'missionId' => $newMission->getId()
                    ]);
                case $formStepFour->get('edit_2')->isClicked() :
                    return $this->redirectToRoute('mission_new_step_three', [
                        'missionId' => $newMission->getId()
                    ]);
            }

            $newMission->setStatusGenerator(Mission::STEP_FOUR);
            $em->flush();

            return $this->redirectToRoute('mission_new_step_four', [
                'missionId' => $newMission->getId()
            ]);
        }

        return $this->render('MissionBundle:MissionGenerator:mission_step_four.html.twig', [
            'form'       => $formStepFour->createView(),
            'mission'    => $newMission,
            'nbAdvisors' => count($missionRepository->getUsersByMission($newMission, false, false)),
            'user'       => $user
        ]);

    }

    /**
     * @param Request $request
     * @param int     $missionId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepFiveAction(Request $request, $missionId)
    {
        /** @var \MissionBundle\Repository\MissionRepository $missionRepository */
        $missionRepository = $this->getDoctrine()->getRepository('MissionBundle:Mission');
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \MissionBundle\Entity\Mission $newMission */
        if (!($newMission = $missionRepository->findOneBy(['id' => $missionId, 'contact' => $user]))) {
            return new Response($this->get('translator')->trans('mission.error.authorized', [], 'MissionBundle'));
        } elseif ($newMission->getStatusGenerator() !== Mission::STEP_FOUR) {
            return $this->redirectToRoute('mission_edit', ['missionId' => $newMission->getId()]);
        }
        $newMission->setOnDraft(false);
        /** @var \Symfony\Component\Form\Form $formStepFive */
        $formStepFive = $this->get('form.factory')
            ->create(StepFiveFormType::class, $newMission)->setData($newMission)
        ;
        $formStepFive->get('firstName')->setData($user->getFirstName());
        $formStepFive->get('lastName')->setData($user->getLastName());

        if ($formStepFive->handleRequest($request)->isSubmitted() && $formStepFive->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($formStepFive->get('back')->isClicked()) {
                $newMission->setStatusGenerator(Mission::STEP_THREE);
                $em->flush();

                return $this->redirectToRoute('mission_edit', [
                    'missionId' => $newMission->getId()
                ]);
            }
            $newMission->setStatus(Mission::PUBLISHED)->setStatusGenerator(Mission::DONE);

            $jsonConfig = json_decode($em->getRepository('ToolsBundle:Config')->findOneConfig()->getValue());
            $i          = 0;
            while ($i < $jsonConfig->nbStep) {
                $i++;
                $step     = 'step' . $i;
                $jsonStep = $jsonConfig->$step;
                $step     = new Step($jsonStep->nbMaxUser, $jsonStep->reallocUser);
                $step
                    ->setMission($newMission)
                    ->setUpdateDate(new \DateTime())
                    ->setPosition($i)
                    ->setAnonymousMode($jsonStep->anonymousMode)
                ;

                if ($i === 1) {
                    $step->setStatus(1);
                }
                $em->persist($step);
            }

            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('MissionBundle:MissionGenerator:mission_step_five.html.twig', [
            'form' => $formStepFive->createView(),
            'user' => $user
        ]);
    }

    /**
     * @param int $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($missionId)
    {
        $trans   = $this->get('translator');
        $em      = $this->getDoctrine()->getManager();
        /** @var \MissionBundle\Entity\Mission $mission */
        $mission = $em->getRepository('MissionBundle:Mission')->findOneBy(['id' => $missionId]);
        $user    = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')
            && $mission != null && $user->getCompany() == $mission->getCompany()) {
            $mission->setStatus(Mission::DELETED);
            $notification = $this->container->get('notification');
            $steps        = $em->getRepository('MissionBundle:Step')->getStepsAvailables($missionId);
            /** @var \MissionBundle\Entity\Step $step */
            foreach ($steps as $step) {
                $step->setStatus(-1);
                $em->persist($step);
            }

            $param = [
                'mission' => $mission->getId(),
                'user'    => $user->getId(),
            ];

            $usersMission = $em->getRepository('MissionBundle:UserMission')->findBy(['mission' => $mission]);
            foreach ($usersMission as $userMission) {
                $userMission->setStatus(UserMission::DELETED);
                $em->persist($userMission);

                // Add notifications for advisors
                $notification->new($userMission->getUser(), 1, 'notification.expert.mission.delete', $param);

            }
            // Add notifications for contractor
            $notification->new($user, 1, 'notification.seeker.mission.delete', $param);

            $em->flush();

            return $this->redirectToRoute('dashboard', []);
        }
        throw new NotFoundHttpException($trans->trans('mission.error.forbiddenAccess', [], 'MissionBundle'));
    }
}
