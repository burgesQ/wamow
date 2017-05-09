<?php

namespace UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\FOSUserEvents;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class FOSUBUserProvider extends BaseClass
{

    /**
     * @var Container
     */
    private $container;

    /**
     * MissionMatching constructor.
     *
     * @param \FOS\UserBundle\Model\UserManagerInterface $fosParam
     * @param array                                      $serviceParam
     * @param Container                                  $container
     */
    public function __construct($fosParam, $serviceParam, Container $container)
    {
        $this->container = $container;
        parent::__construct($fosParam, $serviceParam);
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service      = $response->getResourceOwner()->getName();
        $setter       = 'set' . ucfirst($service);
        $setter_id    = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy([$property => $username])) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $data = $response->getResponse();

        $username = $data['emailAddress'];
        $user     = $this->userManager->findUserByUsernameOrEmail($username);

        //when the user is registrating
        if (null === $user) {

            $service      = $response->getResourceOwner()->getName();
            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';

            // create new user here
            /** @var \UserBundle\Entity\User $user */
            $user = $this->userManager->createUser();
            $user->$setter_id($username)->$setter_token($response->getAccessToken());

            // set user data
            $user
                ->setEmail($username)
                ->setEnabled(true)
                ->setPassword('password')
                ->setRoles(['ROLE_ADVISOR'])
            ;

            if (array_key_exists('firstName', $data))
                $user->setFirstName(ucwords($data['firstName']));
            if (array_key_exists('lastName', $data))
                $user->setLastName(ucwords($data['lastName']));
            if (array_key_exists('headline', $data) && array_key_exists('summary', $data))
                $user->setUserResume($data['headline'] . $data['summary']);
            if (array_key_exists('location', $data) && array_key_exists('country', $data['location'])
                && array_key_exists('code', $data['location']['country'])) {
                $address = new Address();
                $address->setCountry(strtoupper($data['location']['country']['code']));
                $this->container->get('doctrine')->getManager()->persist($address);
                $user->addAddress($address);
            }
            $user->setLinkedinData($data);
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, null);
        } else {
            //if user exists - go with the HWIOAuth way
            $serviceName = $response->getResourceOwner()->getName();
            $setter      = 'set' . ucfirst($serviceName) . 'AccessToken';
            //update access token
            $user->$setter($response->getAccessToken());
        }

        $this->userManager->updateUser($user);

        return $user;
    }

}