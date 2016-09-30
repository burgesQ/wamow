<?php
// src/UserBundle/Twig/CountryExtension.php

namespace UserBundle\Twig;

use Symfony\Component\Intl\Intl;

class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('countryName', array($this, 'countryName')),
        );
    }

    public function countryName($country){
        return Intl::getRegionBundle()->getCountryName($country);
    }

    public function getName()
    {
        return 'country_extension';
    }
}