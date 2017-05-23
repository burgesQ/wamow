<?php

namespace UserBundle\Services;

use UserBundle\Entity\User;

class SignedUp
{
    private $array = [
        'expert_registration_step_zero',
        'expert_registration_step_one',
        'expert_registration_step_two',
        'expert_registration_step_three',
        'expert_registration_step_four'
    ];

    public function checkIfSignedUp($status)
    {
        return  ($status == User::REGISTER_NO_STEP) ? null : $this->array[$status];
    }
}
