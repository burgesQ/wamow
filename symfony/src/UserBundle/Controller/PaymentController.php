<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Stripe\Stripe;

class PaymentController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function payAction(Request $request)
    {
        $stripe = array(
          "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
          "publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
        );
        Stripe::setApiKey($stripe['secret_key']);

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('Not a xml request');
        }

        // get user
        if (!($user = $this->getUser())) {
            throw new NotFoundHttpException();
        }

        $user->setNotification(!$user->getNotification());
        // switch user notification
        $this->getDoctrine()->getManager()->flush();

        return new Response('Oukey');
   }

   public function toPayAction(Request $request)
   {
      $stripe = array(
       "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
       "publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
      );
      Stripe::setApiKey($stripe['secret_key']);

      die('<form action="'.$this->generateUrl('user_pay').'" method="post">
          <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="'.$stripe['publishable_key'].'"
            data-description="Access for a year"
            data-amount="5000"
            data-locale="auto"></script>
          </form>')
      ;
   }
}
