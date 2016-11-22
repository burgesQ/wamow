<?php

namespace UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use FOS\UserBundle\Controller\ProfileController as BaseController;

use ToolsBundle\Entity\Upload;
use ToolsBundle\Entity\ReadResume;

use UserBundle\Form\MergedFormType;

class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getRepository('UserBundle:User');
        
        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'images' => $em->myFindUserPicture($user),
            'resumes' => $em->myFindUserResume($user)
        ));
    }
    
    /**
     * Remove accent and ponc from a string
     */
    public function stripAccents($str)
    {
        $url = $str;

        $url = preg_replace('#Ç#', 'C', $url);
        $url = preg_replace('#ç#', 'c', $url);
        $url = preg_replace('#è|é|ê|ë#', 'e', $url);
        $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
        $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
        $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
        $url = preg_replace('#ì|í|î|ï#', 'i', $url);
        $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
        $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
        $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
        $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
        $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
        $url = preg_replace('#ý|ÿ#', 'y', $url);
        $url = preg_replace('#Ý#', 'Y', $url);

        $url = preg_replace('/\W+/', ' ', $url);

        return $url;    
    }

    /**
     * Extract mail address from cv  
     */
    public function getMail($str)
    {
        $url = $str;
        $pattern="/(?:[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        preg_match_all($pattern, $str, $url);

        return $url;    
    }
    
    /**
     * Parse the resume of the user
     */
    public function parsAction()
    {
        $em = $this->getDoctrine()->getRepository('UserBundle:User');
        $user = $this->getUser();
        $resumes = $em->myFindUserResume($user);
        $resume = reset($resumes);
        
        if ($resume != null) {
            if ( ($resumeParsed = $user->getReadResume()) == null)
                $resumeParsed = new ReadResume();
         
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($resume->getWebPath());
            $wordsToRemove = array(                
                ' mais ', ' ou ', ' et ', ' donc ', ' or ', ' ni ', ' car ',
                ' le ', ' la ', ' les ', ' un ', ' une ', ' des ', ' de ', ' du ',
                ' avec ', ' sans ', ' si ', ' puis ', 
                ' devant ', ' derriere ', ' dessus ', ' dessout ', ' dedans ', ' dans ', ' sur ', 
                ' chez ', ' avec ', ' pour ',
                ' pendant ', ' apres ', ' avant ',
                ' en ', ' au ',
                ' d ', ' a ', ' l ', ' s ', ' m ', ' n ',
                ' ce ', ' ca ', ' cela ', ' se ', ' sont ', ' ses ',
            );

            $mailAdress = $this::getMail($pdf->getText());
            if ($mailAdress[0] != null)
                $user->addSecretMail($mailAdress[0]);
            
            $textToPars = strtolower( $this::stripAccents( $pdf->getText() ) );
            foreach ($wordsToRemove as $word)
                $textToPars = str_replace($word, ' ', $textToPars);

            $resumeParsed->setResumesParsed($textToPars);
            $resumeParsed->setLastParsed($resume->getId());
            $user->setReadResume($resumeParsed);

            $em = $this->getDoctrine()->getManager();
            $em->persist($resumeParsed);
            $em->flush();
        }
    }
    
    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $em = $this->getDoctrine()->getManager();

        $image = new Upload();
        $image->setUser($user);
        $image->setKind(1);
        $image->setType("image");

        $resume = new Upload();
        $resume->setUser($user);
        $resume->setKind(2);
        $resume->setFormat("pdf");
            
        $formData['user'] = $user;
        $formData['image'] = $image;
        $formData['resume'] = $resume;

        $form = $this->createForm(new MergedFormType(), $formData);

        $form->setData($user);
        $form->setData($image);
        $form->setData($resume);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $user->setFirstName(ucwords($user->getFirstName()));
            $user->setLastName(ucwords($user->getLastName()));
            $userManager->updateUser($user);

            if ($image->getFile() != null) {
                $em->persist($image);
            } if ($resume->getFile() != null) {
                $em->persist($resume);
            }        

            $em->flush();

            $this::parsAction();
            
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
