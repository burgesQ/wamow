<?php

namespace ToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MissionBundle\Entity\Step;
use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionType;
use ToolsBundle\Entity\Language;
use ToolsBundle\Form\LanguageType;

class ToolsController extends Controller
{
    public function indexAction()
    {
        $language = new Language();
        $language->setName("french");
        return $this->render('ToolsBundle:Tools:index.html.twig');
    }

}
