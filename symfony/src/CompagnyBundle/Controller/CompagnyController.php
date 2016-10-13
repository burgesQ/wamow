<?php

namespace CompagnyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CompagnyBundle\Entity\Compagny;
use CompagnyBundle\Form\CompagnyType;

class CompagnyController extends Controller
{
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $compagny = new Compagny($user->getId());
        $form = $this->get('form.factory')->create(new CompagnyType(), $compagny);
        $form->handleRequest($request);
        if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($compagny);
                $em->flush();
                $id = $mission->getId();
                return $this->render('CompagnyBundle:Compagny:registered.html.twig', array(
                'mission'  =>   $compagny,
                'form'     =>   $form,
                ));
            }
        return $this->render('CompagnyBundle:Compagny:new.html.twig', array(
            'form' => $form->createView(),
            ));
    }
}
