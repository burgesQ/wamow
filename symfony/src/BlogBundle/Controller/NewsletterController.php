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
        /** @var NewsletterRepository $newsletterRepository */
        $newsletterRepository = $this->getDoctrine()->getRepository('BlogBundle:Newsletter');

        return $this->render('@Blog/NewsLetter/list.html.twig', [
            'newsletters' => $newsletterRepository->getAvailableNewsletters()
        ]);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        if (!($newsletter = $this->getDoctrine()->getRepository('BlogBundle:Newsletter')
                ->findOneBy(['number' => $id])) ||
            $newsletter->getPublishedDate > new \DateTime())
            throw new NotFoundHttpException("No such newsletter");

        $articles = $this->getDoctrine()->getRepository('BlogBundle:Article')
            ->findBy([
                'newsletter' => $newsletter
            ]);

        return $this->render('@Blog/NewsLetter/show.html.twig', [
                'newsletter' => $newsletter,
                'articles'   => $articles
        ]);
    }
}
