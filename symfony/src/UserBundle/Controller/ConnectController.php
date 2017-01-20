<?php

namespace UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseController;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class ConnectController extends BaseController
{
    /**
     * Connects a user to a given account if the user is logged in and connect is enabled.
     *
     * @param Request $request The active request.
     * @param string  $service Name of the resource owner to connect to.
     *
     * @throws \Exception
     *
     * @return Response
     *
     * @throws NotFoundHttpException if `connect` functionality was not enabled
     * @throws AccessDeniedException if no user is authenticated
     *
     * Mod from HWIOAuthBundle\Controller\ConnectController:connectServiceAction
     * so in the end the controller redirect to the root of the webSite
     */
    public function connectServiceAction(Request $request, $service)
    {
        $connect = $this->container->getParameter('hwi_oauth.connect');
        if (!$connect)
        {
            throw new NotFoundHttpException();
        }

        $hasUser = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if (!$hasUser)
        {
            throw new AccessDeniedException('Cannot connect an account.');
        }

        // Get the data from the resource owner
        $resourceOwner = $this->getResourceOwnerByName($service);

        $session = $request->getSession();
        $key = $request->query->get('key', time());

        if ($resourceOwner->handles($request))
        {
            $accessToken = $resourceOwner->getAccessToken(
                $request,
                $this->container->get('hwi_oauth.security.oauth_utils')->getServiceAuthUrl($request, $resourceOwner)
            );

            // save in session
            $session->set('_hwi_oauth.connect_confirmation.'.$key, $accessToken);
        }
        else
        {
            $accessToken = $session->get('_hwi_oauth.connect_confirmation.'.$key);
        }

        // Redirect to the login path if the token is empty (Eg. User cancelled auth)
        if (null === $accessToken)
        {
            return $this->redirectToRoute($this->container->getParameter('hwi_oauth.failed_auth_path'));
        }

        $userInformation = $resourceOwner->getUserInformation($accessToken);

        // Show confirmation page?
        if (!$this->container->getParameter('hwi_oauth.connect.confirmation'))
        {
            goto show_confirmation_page;
        }

        // Symfony <3.0 BC
        /** @var $form FormInterface */
        $form = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
            ? $this->createForm('Symfony\Component\Form\Extension\Core\Type\FormType')
            : $this->createForm('form');
        // Handle the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            show_confirmation_page:

            /** @var $currentToken OAuthToken */
            $currentToken = $this->getToken();
            $currentUser = $currentToken->getUser();

            $this->container->get('hwi_oauth.account.connector')->connect($currentUser, $userInformation);

            if ($currentToken instanceof OAuthToken)
            {
                // Update user token with new details
                $newToken =
                    is_array($accessToken) &&
                    (isset($accessToken['access_token']) || isset($accessToken['oauth_token'])) ?
                        $accessToken : $currentToken->getRawToken();

                $this->authenticateUser($request, $currentUser, $service, $newToken, false);
            }
            return $this->redirect("/");
        }

        return $this->render('HWIOAuthBundle:Connect:connect_confirm.html.' . $this->getTemplatingEngine(), [
            'key' => $key,
            'service' => $service,
            'form' => $form->createView(),
            'userInformation' => $userInformation,
        ]);
    }

}