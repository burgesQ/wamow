<?php

namespace UserBundle\Controller;

use MissionBundle\Entity\UserWorkExperience;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use UserBundle\Form\RegistrationAdvisor\MergedFormRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form\RegistrationAdvisor\StepThreeType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use UserBundle\Form\RegistrationAdvisor\StepFourType;
use UserBundle\Form\RegistrationAdvisor\StepOneType;
use UserBundle\Form\RegistrationAdvisor\StepTwoType;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use ToolsBundle\Entity\UploadResume;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;
use Swift_Message;

class RegistrationAdvisorController extends Controller
{
    /**
     * manage signUp Step0 Expert
     *
     * @param Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepZeroAction(Request $request)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher  = $this->container->get('event_dispatcher');
        $session     = new Session(new PhpBridgeSessionStorage());
        $userManager = $this->get('fos_user.user_manager');
        $em          = $this->getDoctrine()->getManager();
        $trans       = $this->get('translator');

        $session->start();
        $session->set('role', 'ADVISOR');

        if (!($user = $this->getUser())) {
            /** @var \UserBundle\Entity\User $user */
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setRoles(["ROLE_ADVISOR"]);
        } elseif (($url = $this->get('signed_up')->checkIfSignedUp($user->getStatus()))) {
            $this->redirectToRoute($url);
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        // create Resume entity
        $resume = new UploadResume($user);
        $resume->setKind(2);

        // create a Address entity
        if ($user->getAddresses()->isEmpty()) {
            $address = new Address();
            $user->addAddress($address);
            $em->persist($address);
        }

        // create merged form
        $formData['user']   = $user;
        $formData['resume'] = $resume;
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new MergedFormRegistrationType(), $formData);
        $form->setData($user)->setData($resume);

        // if form submitted
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if (!$user->getLinkedinId() && !$form->get('resume')->getData()->getFile()) {
                $form->get('resume')->addError(new FormError($trans->trans('error.upload_resume_or_linkedin', [], 'tools')));
            } elseif($userManager->findUserBy(['email' => $form->get('user')->get('email')->getData()]) !== null &&
                (!$this->getUser() || $this->getUser()->getEmail() !== $form->get('user')->get('email')->getData())) {

                $form->get('user')->get('email')->addError(new FormError($trans->trans('error.user.email_in_use', [],
                    'tools')));
            } else {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $user->setFirstName(ucwords($user->getFirstName()));
                $user->setLastName(ucwords($user->getLastName()));
                $user->setStatus(User::REGISTER_STEP_ONE);

                $password = $this->get('hackzilla.password_generator.computer')
                    ->setLowercase()->setUppercase()->setNumbers()->setSymbols()
                    ->setAvoidSimilar()->setLength(10)->generatePassword();

                $trans = $this->get('translator');

                $user->setPlainPassword($password);
                $userManager->updateUser($user);

                $user->setPublicId(md5(uniqid() . $user->getUserResume() . $user->getId()));
                $resume->setUser($user);
                $em->persist($resume);
                $em->flush();

                $message = Swift_Message::newInstance()
                    ->setSubject($trans->trans('mails.subject.new_password', [], 'tools'))
                    ->setFrom($this->container->getParameter('email_sender'))
                    ->setTo($user->getEmail())/* put a valid email address there to test */
                    ->setBody($this->renderView('Emails/new_password.html.twig', [
                        'f_name'   => $user->getFirstName(),
                        'l_name'   => $user->getLastName(),
                        'password' => $password
                    ]), 'text/html')
                ;
                $this->get('mailer')->send($message);

                if (null === $response = $event->getResponse()) {
                    $response = new RedirectResponse($this->generateUrl('expert_registration_step_one'));
                }
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED,
                    new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->render('UserBundle:Registration:register_expert.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * manage signUp Step1 Expert
     *
     * @param Request $request
     *
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepOneAction(Request $request)
    {

        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() < User::REGISTER_STEP_ONE) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepOneType(), $user)->setData($user);
        // if form submitted
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $user->setStatus(User::REGISTER_STEP_TWO);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_two');
        }

        $arrayImg = [
            "businesspractice.industry" => "manufacturing",
            "businesspractice.finance" => "finance",
            "businesspractice.retail" => "retail",
            "businesspractice.media" => "media-telco-entertainment",
            "businesspractice.tourism" => "tourisme",
            "businesspractice.construction" => "construction",
            "businesspractice.realestate" => "finance",
            "businesspractice.hotel" => "hotel",
            "businesspractice.services" => "food-beverage",
            "businesspractice.energy" => "energy",
            "businesspractice.it" => "it",
            "businesspractice.public" => "public",
            "businesspractice.ngo" => "ngo"
        ];

        return $this->render('UserBundle:Registration:register_expert_step_one.html.twig', [
            'form'     => $form->createView(),
            'arrayImg' => $arrayImg
        ]);
    }

    /**
     * manage signUp Step2 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepTwoAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() < User::REGISTER_STEP_TWO) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepTwoType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $user->setStatus(User::REGISTER_STEP_THREE);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_three');
        }

        return $this->render('UserBundle:Registration:register_expert_step_two.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * manage signUp Step3 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepThreeAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() < User::REGISTER_STEP_THREE) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepThreeType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $user->setStatus(User::REGISTER_STEP_FOUR);
            $this->get('fos_user.user_manager')->updateUser($user);

            return $this->redirectToRoute('expert_registration_step_four');
        }

        return $this->render('UserBundle:Registration:register_expert_step_three.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * manage signUp Step4 Expert
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stepFourAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_registration_register_expert');
        } elseif ($user->getStatus() < User::REGISTER_STEP_FOUR) {
            return $this->redirectToRoute($this->get('signed_up')->checkIfSignedUp($user->getStatus()));
        }

        $em = $this->getDoctrine()->getManager();
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->createForm(new StepFourType(), $user)->setData($user);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {

            $workExpRepo     = $em->getRepository('MissionBundle:WorkExperience');
            $companySizeRepo = $em->getRepository('MissionBundle:CompanySize');
            $continentRepo   = $em->getRepository('MissionBundle:Continent');

            $smallComp    = $companySizeRepo->findOneBy(['name' => 'company_size.small']);
            $mediumComp   = $companySizeRepo->findOneBy(['name' => 'company_size.medium']);
            $largeComp    = $companySizeRepo->findOneBy(['name' => 'company_size.large']);
            $southAmerica = $continentRepo->findOneBy(['name' => 'continent.south_america']);
            $northAmerica = $continentRepo->findOneBy(['name' => 'continent.north_america']);
            $asia         = $continentRepo->findOneBy(['name' => 'continent.asia']);
            $emea         = $continentRepo->findOneBy(['name' => 'continent.emea']);

            foreach ($form->get('userWorkExpSerialized')->getData() as $key => $val) {
                if ($val) {
                    $array = explode('&', str_replace('__name__', $key, urldecode($val)));

                    /** @var UserWorkExperience $userWorkExperience */
                    $userWorkExperience = new UserWorkExperience();
                    $user->addUserWorkExperience($userWorkExperience);
                    $userWorkExperience->setUser($user);
                    $userWorkExperience->setWorkExperience($workExpRepo->findOneBy(['id' => $key]));

                    foreach ($array as $value) {
                        $arrayTmp = explode('[', $value);
                        $label    = $arrayTmp[3];
                        switch ($label) {
                            case ($label == "companySizes]") :
                                $whichOne = $arrayTmp[4];
                                switch ($whichOne) {
                                    case ($whichOne == "]=1") :
                                        $userWorkExperience->addCompanySize($smallComp);
                                    case ($whichOne == "]=2") :
                                        $userWorkExperience->addCompanySize($mediumComp);
                                    case ($whichOne == "]=3") :
                                        $userWorkExperience->addCompanySize($largeComp);
                                }
                            case ($label == "continent]") :
                                $whichOne = $arrayTmp[4];
                                switch ($whichOne) {
                                    case ($whichOne == "]=1") :
                                        $userWorkExperience->addContinent($asia);
                                    case ($whichOne == "]=2") :
                                        $userWorkExperience->addContinent($emea);
                                    case ($whichOne == "]=3") :
                                        $userWorkExperience->addContinent($northAmerica);
                                    case ($whichOne == "]=4") :
                                        $userWorkExperience->addContinent($southAmerica);
                                }

                            case (substr($label, 0, 12) == "cumuledMonth") :
                                $lastTmp = explode('=', $label);
                                $i       = 0;
                                foreach ($lastTmp as $lastLoop) {
                                    if ($i) {
                                        $userWorkExperience->setCumuledMonth($lastLoop);
                                    }
                                    $i++;
                                }
                            case (substr($label, 0, 10) == "peremption") :
                                $lastTmp = explode('=', $label);
                                $i       = 0;
                                foreach ($lastTmp as $lastLoop) {
                                    if ($i) {
                                        $userWorkExperience->setPeremption(true);
                                    }
                                    $i++;
                                }
                            case (substr($label, 0, 9) == "dailyFees") :
                                $lastTmp = explode('=', $label);
                                $i       = 0;
                                foreach ($lastTmp as $lastLoop) {
                                    if ($i) {
                                        $userWorkExperience->setDailyFees($lastLoop);
                                    }
                                    $i++;
                                }
                        }
                    }
                    $em->persist($userWorkExperience);
                }
            }
            $user->setStatus(User::REGISTER_NO_STEP);
            $this->get('fos_user.user_manager')->updateUser($user);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('UserBundle:Registration:register_expert_step_four.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
