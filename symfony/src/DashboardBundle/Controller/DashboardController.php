<?php
namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use MissionBundle\Form\StepType;
use ToolsBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DashboardController extends Controller
{
    public function indexAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')) {
            return $this->render('DashboardBundle:Expert:index.html.twig');
        }
        elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONTRACTOR')) {
            return $this->render('DashboardBundle:Seeker:index.html.twig');
        }
        return $this->render('DashboardBundle:Default:index.html.twig');
    }
}
