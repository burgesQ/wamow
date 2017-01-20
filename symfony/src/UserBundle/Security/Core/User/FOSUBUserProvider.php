<?php
namespace UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

use Doctrine\ORM\EntityManager;

class FOSUBUserProvider extends BaseClass
{

    protected $em;
    public function __construct($fosParams, $fbParms, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($fosParams, $fbParms);
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        $data = $response->getResponse();

        // on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        // we "disconnect" previously connected users
        if (null !== ($previousUser = $this->userManager->findUserBy(array($property => $username))))
        {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        if (!($this->em->getRepository('UserBundle:User')->checkEmailIsUnique($data['emailAddress'])))
        {
            //we connect current user
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());

            // we update user Datas
            $user->setEmail($data['emailAddress']);
            $user->setStatus(0);
            $this->userManager->updateUser($user);
        }
        else
        {
            $user->setEmail("");
            $user->setStatus(42);
            $this->userManager->updateUser($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        // start a new session
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $role = $session->get('role');

        // get data and user Entity
        $data = $response->getResponse();
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        // tcheck if email address not already in use
        if (count($this->em->getRepository('UserBundle:User')->checkEmailIsUnique($data['emailAddress'])))
            throw new UsernameNotFoundException('Username or email has been already used.');

        // when the user is registrating
        if (null === $user)
        {
            // get the service en set the ServiceToken
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $user->setRoles([(($role === "ADVISOR") ? "ROLE_ADVISOR" : "ROLE_CONTRACTOR")]);

            if ($service === "linkedin")
            {
                $fullname = explode(" ", $data['formattedName']);

                $user->setFirstName($fullname[0]);
                $user->setLastName($fullname[1]);
                $user->setEmail($data['emailAddress']);
                $user->setUsername($data['emailAddress']);
                $user->setPlainPassword($username);
                $user->setPasswordSet(false);
                $user->setEnabled(true);
                $this->userManager->updateUser($user);

                return $user;
            }
            else if ($service === "google")
            {
                $user->setLastName($response->getLastname());
                $user->setFirstName($response->getFirstname());
                $user->setUsername($username);
                $user->setEmail($response->getEmail());
                $user->setPlainPassword($username);
                $user->setPasswordSet(false);
                $user->setEnabled(true);
                $this->userManager->updateUser($user);

                return $user;
            }
        }

        // if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        // update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
}
