<?php

namespace BlogBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Repository\ArticleRepository;
use BlogBundle\Repository\CommentRepository;
use BlogBundle\Form\CommentType;
use Symfony\Component\Form\Form;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param         $url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $url)
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        /** @var CommentRepository $commentRepository */
        $commentRepository = $this->getDoctrine()->getRepository('BlogBundle:Comment');
        // get the article by url (url = article->url)
        $article = $articleRepository->findOneBy(['url' => $url]);

        // if no article match the url throw error
        if ($article === null || $article->getPublishedDate() > new \DateTime())
            throw new NotFoundHttpException($this->get('translator')->trans('newsletter.view.error.not_found', [], 'tools'));

        // create form and handle request
        /** @var Form $form */
        $form = $this->get('form.factory')->create(new CommentType());
        $form->handleRequest($request);

        // handle form submitted
        if ($form->isSubmitted() && $form->isValid()) {
            // get comment and entityManager
            $comment = $form->getData();
            $em = $this->getDoctrine()->getManager();

            // manually set data for the comment
            $comment->setArticle($article);

            // save data of the new comment
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article_view', [
                'url'     => $article->getUrl()
            ]);
        // if form isn't submitted and user is logged then set the authorEmail field
        } elseif (($user = $this->getUser())) {
            $form->get('emailAuthor')->setData($user->getEmail());
        }

        // get comments by articles and status
        $comments = $commentRepository->getCommentsByArticleIfAvailable($article);

        // render the article
        return $this->render('BlogBundle:Article:view.html.twig', [
            'article'     => $article,
            'comments'    => $comments,
            'form'        => $form->createView(),
        ]);
    }
}