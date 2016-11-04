src/MissionBundle/Controller/MissionController.php <?php

namespace MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use TeamBundle\Entity\Team;

class MissionController extends Controller
{
    /*
      Create a new mission
    */
    public function newAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')
            ->isGranted('ROLE_CONTRACTOR'))
        {
            $em = $this->getDoctrine()->getManager();
            $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('MissionBundle:Mission')
                        ;
            $service = $this->container->get('mission.nbStep');
            $nbStep = $service->getMissionNbStep();
            $user = $this->getUser();
            $token = bin2hex(random_bytes(10));
            while ($repository->findByToken($token) != null)
            {
                $token = bin2hex(random_bytes(10));
            }
            $mission = new Mission($nbStep, $user->getId(), 1, $token);
            $form = $this->get('form.factory')->create(new MissionType(), $mission);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em->persist($mission);
                for ($i=0; $i < $nbStep; $i++)
                {
                    $array = $service->getMissionStep($i);
                    $step = new Step($array["nbMaxTeam"], $array["reallocTeam"]);
                    $step->setMission($mission);
                    $step->setPosition($i + 1);
                    $em->persist($step);
                }
                $em->flush();
                $id = $mission->getId();
                return $this->render('MissionBundle:Mission:registered.html.twig', array(
                    'mission'  =>   $mission,
                    'id'       =>   $id,
                    'form'     =>   $form,
                ));
            }
            return $this->render('MissionBundle:Mission:new.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else
        {
            throw new NotFoundHttpException("You're not authorized to create a mission.");
        }
    }

    /*
    ** If contractor -> edit if he created else error
    ** If advisor -> kick
    ** If not log -> kick
    */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')->find($id);

        if ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') ||
             $this->getUser() === null ) {
            throw new NotFoundHttpException("Only a logged contractor can edit a mission.");
        } elseif ( null === $mission ) {
            throw new NotFoundHttpException("The mission ".$id." doesn't exist.");
        } elseif ( $this->getUser()->getId() !== $mission->getIDContact() ) {
            throw new NotFoundHttpException("You can't edit a mission that doesn't belong to you.");
        }

        $form = $this->get('form.factory')->create(new MissionType(), $mission);
        $form->handleRequest($request);
        $mission->setUpdateDate(new \DateTime());

        if ($form->isValid()) {
            $em->flush();
            return new Response('Mission updated successfully');
        }
        return $this->render('MissionBundle:Mission:edit.html.twig', array(
            'form' => $form->createView(),
            'mission' => $mission,
        ));
    }

    /*
    ** If contracotr -> show if created by him else error
    ** If advisor -> show if status === 1
    ** if not log -> kick
    */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        if ( $this->getUser() === null ) {
            throw new NotFoundHttpException("Error : you are neither loged as advisor, nor contractor.");
        } elseif ( ($mission = $em->getRepository('MissionBundle:Mission')->find($id)) === null) {
            throw new NotFoundHttpException("The mission ".$id." doesn't exist.");
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR') &&
                   $mission->getStatus() !== 1 ) {
            throw new NotFoundHttpException("The mission ".$id." isn't available.");
        } elseif ( $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR') &&
                   $mission->getIDContact() !== $this->getUser()->getId() ) {
            throw new NotFoundHttpException("The mission ".$id." wasn't created by you.");
        }

        $listLanguage = $mission->getLanguages();
        return $this->render('MissionBundle:Mission:view.html.twig', array(
            'mission'           => $mission,
            'listLanguage'      => $listLanguage, ) );
    }

    /*
    ** If contractor -> show mission by him
    ** If advisor -> show mission where status === 1
    ** If not log -> kick
    */
    public function missionListAction()
    {
        $repository = $this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('MissionBundle:Mission');
        $user = $this->getUser();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            $listMission = $repository->findByiDContact($user->getId());
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            $listMission = $repository->missionsAvailables();
        } elseif ( $user === null ) {
            throw new NotFoundHttpException("Error : you are neither loged as advisor, nor contractor.");
        }
        
        $role = $user->getRoles();
        return $this->render('MissionBundle:Mission:all_missions.html.twig', array(
            'listMission'           => $listMission,
            'role'                  => $role[0], ) );
    }

    public function missionPitchAction()
    {
        // Team creations
        return new Response("Pitch done");
    }
}
