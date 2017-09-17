<?php

namespace BlogBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Repository\NewsletterRepository;

class NewsletterController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->redirect($this->container->getParameter('news_url') . 'archives');

//        return $this->render('@Blog/NewsLetter/list.html.twig', [
//            'newsletters' => $this->getDoctrine()->getRepository('BlogBundle:Newsletter')
//                ->getAvailableNewsletters(),
//            'home' => 1,
//            'user' => $this->getUser()
//        ]);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->redirect($this->container->getParameter('news_url') . 'newsletters/' . $id . '/index.html');

//        if (!($newsletter = $this->getDoctrine()->getRepository('BlogBundle:Newsletter')
//                ->findOneBy(['number' => $id])) ||
//            $newsletter->getPublishedDate > new \DateTime())
//            throw new NotFoundHttpException("No such newsletter");
//
//        $articles = $this->getDoctrine()->getRepository('BlogBundle:Article')
//            ->findBy(['newsletter' => $newsletter]);
//
//        return $this->render('@Blog/NewsLetter/show.html.twig', [
//            'newsletter' => $newsletter,
//            'articles'   => $articles
//        ]);
    }
}
