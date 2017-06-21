<?php

namespace UserBundle\Services;

use UserBundle\Entity\User;

class Stripe
{
    public function __construct($stripeId)
    {
        $this->stripeId = $stripeId;
    }
}
