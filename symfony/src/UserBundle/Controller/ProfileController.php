<?php

namespace UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use UserBundle\Form\EditProfileMergedFormType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\EditPasswordFormType;
use FOS\UserBundle\Model\UserInterface;
use ToolsBundle\Entity\ProfilePicture;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class ProfileController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        if (!is_object(($user = $this->getUser())) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                  && ($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            return $this->redirectToRoute($url);
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

        $em                  = $this->getDoctrine()->getManager();
        $image               = new ProfilePicture();
        $arrayData           = [
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
            $arrayData, $arrayOption)->setData($arrayData)
        ;
        $formPassword        = $this->createForm(new EditPasswordFormType(User::class))->setData($user);

        if (($formPassword->handleRequest($request)->isSubmitted() && $formPassword->isValid())
            || ($form->handleRequest($request)->isSubmitted() && $form->isValid())) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $user->setFirstName(ucwords($user->getFirstName()));
            $user->setLastName(ucwords($user->getLastName()));

            if ($image->getFile()) {
                $user->addImage($image);
            }

            if ($newAddress !== null) {
                $em->persist($newAddress);
            }

            $userManager->updateUser($user);
            $em->flush();
            $url      = $this->generateUrl('user_profile_show');
            $response = new RedirectResponse($url);

            return $response;
        }

        return $this->render('UserBundle:Profile:edit.html.twig', [
            'form'         => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'user'         => $user
        ]);
    }
}
