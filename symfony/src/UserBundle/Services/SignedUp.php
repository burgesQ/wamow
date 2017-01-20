<?php

namespace UserBundle\Services;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SignedUp
{

    public function checkIfSignedUp($context)
    {
        if (($status = $context->getUser()->getStatus()) == 5)
            return null;

        // if ($status < 0) {}       

        if ($status == 42)
            return  'fos_user_registration_register_expert';

        $array = [ 'expert_registration_step_one',
                   'expert_registration_step_two',
                   'expert_registration_step_three',
                   'expert_registration_step_four',
                   'expert_registration_step_five' ];

        return $array[$status];
    }
    
}
