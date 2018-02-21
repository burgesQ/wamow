<?php

namespace ToolsBundle\Service;

use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WamowMailerService
 *
 * @package ToolsBundle\Service
 */
class WamowMailerService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translator;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $senders;

    /**
     * WamowMailerService constructor.
     *
     * @param \Swift_Mailer                             $mailer
     * @param \Symfony\Component\Translation\Translator $translator
     * @param ContainerInterface                        $container
     */
    public function __construct($mailer, $translator, $container)
    {

        $this->mailer     = $mailer;
        $this->translator = $translator;
        $this->container  = $container;
        $this->senders    = [
            ['ana.expert@wamow.co', 'mails.sign.ana_sign'],
            ['emma.expert@wamow.co', 'mails.sign.emma_sign']
        ];
    }

    /**
     * Send the preRegister email
     *
     * @param string $subject
     * @param string $target
     * @param string $view
     * @param array  $data
     */
    public function sendWamowMails($subject, $target, $view, $data)
    {
        $sender = $this->getSenderSignature();

        $data += [
            'signature' => $sender[1]
        ];

        $message = Swift_Message::newInstance()
            ->setSubject($this->translator->trans($subject, [], 'tools'))
            ->setFrom($sender[0])
            ->setTo($target)
            ->setBody(
                $this->renderTemplate($view, $data),
                'text/html');

        $this->mailer->send($message);
    }

    /**
     * Return a random array w/ Sender email & signature
     *
     * @return array
     */
    public function getSenderSignature()
    {
        return $this->senders[rand(0, count($this->senders) - 1)];
    }

    /**
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function renderTemplate($view, $data)
    {
        return $this->container->get('twig')->render($view, $data);
    }

}