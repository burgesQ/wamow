<?php

namespace HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class DefaultController extends Controller
{
    public function expertAction(Request $request)
    {
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'ADVISOR');
        return $this->render('HomePageBundle:Expert:home.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    public function seekerAction(Request $request)
    {
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $session->set('role', 'CONTRACTOR');
        return $this->render('HomePageBundle:Seeker:home.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
