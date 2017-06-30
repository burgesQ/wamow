<?php

namespace UserBundle\Controller;

use MissionBundle\Entity\UserMission;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form\EditCertificationFormType;
use UserBundle\Form\EditProfileMergedFormType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\EditPasswordFormType;
use FOS\UserBundle\Model\UserInterface;
use ToolsBundle\Entity\ProfilePicture;
use Symfony\Component\Form\FormError;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class ProfileController extends Controller
{
    /**
     * Display data about user
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request)
    {
        if (!is_object(($user = $this->getUser())) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            if ($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus())) {
                return $this->redirectToRoute($url);
            }
            $form = $this->createForm(new EditCertificationFormType(), $user);
            if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
                $this->get('fos_user.user_manager')->updateUser($user);

                return $this->redirectToRoute('user_profile_show');
            }

            return $this->render('UserBundle:Profile:show.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }

        return $this->render('UserBundle:Profile:show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Edit the user
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        if (!is_object($user = $this->getUser()) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
            && ($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            return $this->redirectToRoute($url);
        }

        $em        = $this->getDoctrine()->getManager();
        $image     = new ProfilePicture();
        $arrayData = [
            'user'  => $user,
            'image' => $image
        ];

        $newAddress = null;
        if ($user->getAddresses()->isEmpty()) {
            $newAddress = new Address();
            $user->addAddress($newAddress);
        }

        $arrayOption['role'] = $user->getRoles()[0];
        $form                = $this->createForm(EditProfileMergedFormType::class,
            $arrayData, $arrayOption)->setData($arrayData);
        $formPassword        = $this->createForm(new EditPasswordFormType(User::class))->setData($user);
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');


        if ($formPassword->handleRequest($request)->isSubmitted()) {
            if ($formPassword->isValid()) {
                $userManager->updateUser($user);

                return $this->redirectToRoute('user_profile_show');
            }
        } elseif ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if ($form->get('user')->get('phone')->get('prefix')->isEmpty()
                || $form->get('user')->get('phone')->get('number')->isEmpty()) {
                $form->get('user')->get('phone')->addError(new FormError($this->get('translator')
                    ->trans('error.phone.please_fill', [],
                        'tools')));
            } else {
                $user->setFirstName(ucwords($user->getFirstName()));
                $user->setLastName(ucwords($user->getLastName()));

                if ($image->getFile()) {
                    $user->addImage($image);
                }
                if ($newAddress !== null) {
                    $em->persist($newAddress);
                }
                $userManager->updateUser($user);

                return $this->redirectToRoute('user_profile_show');
            }
        }

        return $this->render('UserBundle:Profile:edit.html.twig', [
            'form'         => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'user'         => $user
        ]);
    }

    /**
     * Display data about the user userId
     *
     * @param                                           $missionId
     * @param integer                                   $userId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($missionId, $userId)
    {
        /** @var \UserBundle\Entity\User $user */
        if (!is_object(($user = $this->getUser())) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') &&
            ($adv = $this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(['id' => $userId])) &&
            ($userMission = $this->getDoctrine()->getRepository('MissionBundle:UserMission')
                ->findUserMissionByCompanyAndUser($missionId, $userId)) &&
            ($userMission->getStatus() >= UserMission::SHORTLIST)
        ) {
            return $this->render('UserBundle:Profile:view.html.twig', [
                'user'      => $adv,
                'missionId' => $missionId
            ]);
        }
        throw new NotFoundHttpException('You cannot see this profile');
    }
}
