<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\{
    HttpKernel\Exception\NotFoundHttpException,
    HttpFoundation\Request
};

class NewsLetterController extends Controller
{
    public function listAction()
    {
        return $this->render('BlogBundle:NewsLetter:list.html.twig', [
            'newsLetters' => $this->getDoctrine()->getRepository('BlogBundle:NewsLetter')->getAvailableNewsLetters(),
        ]);
    }

    public function showAction($id)
    {
        if (!($newsLetter = $this->getDoctrine()->getRepository('BlogBundle:NewsLetter')->findOneById($id)) ||
            $newsLetter->getPublishedDate > new \DateTime())
            throw new NotFoundHttpException($this->get('translator')->trans('view.error.notFound'));

        return $this->render('BlogBundle:NewsLetter:show.html.twig', [
            'newsLetter' => $newsLetter,
        ]);
    }

}
