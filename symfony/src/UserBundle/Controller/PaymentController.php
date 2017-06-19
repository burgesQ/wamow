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
    public function stripeAction(Request $request)
    {
        Stripe::setApiKey($this->container->getParameter("stripe_secret_key"));

        // get user
        if (!($user = $this->getUser())) {
            throw new NotFoundHttpException();
        }

        $token  = $_POST['stripeToken'];

        $customer = \Stripe\Customer::create(array(
            'email' => $user->getEmail(),
            'source'  => $token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $this->container->getParameter("stripe_subscription_price") * 100,
            'currency' => $this->container->getParameter('stripe_subscription_currency')
        ));

        $user->setPlanPaymentProvider("stripe");
        $user->setPlanPaymentAmount($this->container->getParameter("stripe_subscription_price"));
        $user->setPlanType("ADVISOR_PLAN_V1");
        $user->setPlanSubscripbedAt(new \DateTime());
        $user->setPlanExpiresAt(new \DateTime("+1 months"));

        $this->getDoctrine()->getManager()->flush();

        $trans = $this->get('translator');
        $request->getSession()->getFlashBag()->add('notice', $trans->trans('payment.ok', [], 'tools'));

        return $this->redirect($request->headers->get('referer'));
   }
}
