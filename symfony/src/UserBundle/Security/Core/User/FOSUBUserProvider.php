<?php
namespace UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
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
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        $role = $session->get('role');

        $data = $response->getResponse();
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
       //when the user is registrating
        if (null === $user) {
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        // create new user here
        $user = $this->userManager->createUser();
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        //I have set all requested data with the user's username
        //modify here with relevant data


        if ($role === "ADVISOR")
          $user->setRoles(array("ROLE_ADVISOR"));
        else if ($role === "CONTRACTOR")
          $user->setRoles(array("ROLE_CONTRACTOR"));
        if ($service === "linkedin") {
          //Linkedin
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
        else if ($service === "google") {
        //Google
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

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
}
