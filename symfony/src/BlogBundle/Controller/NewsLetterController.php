<?php

namespace BlogBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Repository\NewsLetterRepository;

class NewsLetterController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        /** @var NewsLetterRepository $newsLetterRepository */
        $newsLetterRepository = $this->getDoctrine()->getRepository('BlogBundle:NewsLetter');

        return $this->render('BlogBundle:NewsLetter:list.html.twig', [
            'newsLetters' => $newsLetterRepository->getAvailableNewsLetters()
        ]);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        if (!($newsLetter = $this->getDoctrine()->getRepository('BlogBundle:NewsLetter')
                ->findOneBy(['number' => $id])) ||
            $newsLetter->getPublishedDate > new \DateTime())
            throw new NotFoundHttpException("No such newsLetter");

        $articles = $this->getDoctrine()->getRepository('BlogBundle:Article')
            ->findBy([
                'newsLetter' => $newsLetter
            ]);

        return $this->render('BlogBundle:NewsLetter:show.html.twig', [
                'newsLetter' => $newsLetter,
                'articles'   => $articles
        ]);
    }
}
