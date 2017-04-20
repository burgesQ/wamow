<?php

namespace UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use ToolsBundle\Entity\ProfilePicture;
use ToolsBundle\Entity\UploadResume;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;

class ProfileController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        if (!is_object(($user = $this->getUser())) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                  && ($url = $this->get('signedUp')->checkIfSignedUp($user->getStatus()))) {

            return $this->redirectToRoute($url);
        }

        $em = $this->getDoctrine()->getManager();

        return $this->render('UserBundle:Profile:show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface)
        {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        else if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                 && ($url = $this->get('signedUp')->checkIfSignedUp($user->getStatus())))
        {
            return $this->redirectToRoute($url);
        }
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse())
            return $event->getResponse();

        $em = $this->getDoctrine()->getManager();

        $image = new ProfilePicture($user);
        $image->setUser($user)->setKind(1)->setType("image");
        
        $resume = new UploadResume($user);
        $resume->setUser($user)->setKind(2)->setFormat("pdf");
        
        $formData['user'] = $user;
        $formData['image'] = $image;
        $formData['resume'] = $resume;

        $form = $this->createForm(new MergedFormType(), $formData);

        $form->setData($user)->setData($image)->setData($resume);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $user->setFirstName(ucwords($user->getFirstName()));
            $user->setLastName(ucwords($user->getLastName()));
            $userManager->updateUser($user);

            if ($image->getFile() != null)
            {
                $em->persist($image);
            }
            if ($resume->getFile() != null)
            {
                $em->persist($resume);
            }
            $em->flush();
            
            $parser = $this->get('user.services');
            $parser->parseResume($this->getDoctrine()->getRepository('ToolsBundle:UploadResume'),
                                 $user,
                                 $this->getDoctrine()->getManager());

            if (null === $response = $event->getResponse()) {                
                $url = $this->generateUrl('user_profile_show');
                $response = new RedirectResponse($url);
            }
            
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
            return $response;
        }

        return $this->render('UserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
