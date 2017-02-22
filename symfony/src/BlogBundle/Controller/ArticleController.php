<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\{
    HttpKernel\Exception\NotFoundHttpException,
    HttpFoundation\Request
};

use BlogBundle\Form\CommentType;

class ArticleController extends Controller
{
    public function viewAction(Request $request, $url)
    {
        // get the article by url (url = article.url)
        $article = $this->getDoctrine()->getRepository('BlogBundle:Article')->findOneByUrl($url);

        // if no article match the url throw error
        if ($article === null || $article->getPublishedDate() > new \DateTime())
            throw new NotFoundHttpException($this->get('translator')->trans('view.error.notFound'));

        // create form and handle request
        $form = $this->get('form.factory')->create(new CommentType());
        $form->handleRequest($request);

        // handle form submitted
        if ($form->isSubmitted() && $form->isValid())
        {
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
        }
        // if form isn't submitted and user is logged then set the authorEmail field
        elseif (($user = $this->getUser()))
            $form->get('emailAuthor')->setData($user->getEmail());

        // get comments by articles and status
        $comments = $this->getDoctrine()->getRepository('BlogBundle:Comment')->getCommentsByArticleIfAvailable($article);

        // render the article
        return $this->render('BlogBundle:Article:view.html.twig', [
            'article'     => $article,
            'comments'    => $comments,
            'form'        => $form->createView(),
        ]);
    }

}