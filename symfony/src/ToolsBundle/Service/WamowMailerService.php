<?php

namespace ToolsBundle\Service;

use Swift_Message;

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
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $senders;

    /**
     * WamowMailerService constructor.
     *
     * @param \Swift_Mailer                             $mailer
     * @param \Symfony\Component\Translation\Translator $translator
     * @param \Twig_Environment                         $twig
     */
    public function __construct($mailer, $translator, $twig)
    {

        $this->mailer     = $mailer;
        $this->translator = $translator;
        $this->twig       = $twig;
        $this->senders    = [
            ['ana@wamow.co', 'mails.inbox.sign.ana_sign'],
            ['emmaexpert@wamow.co', 'mails.sign.ana_sign']
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
    private function getSenderSignature()
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
        return $this->twig->render($view, $data);
    }

}