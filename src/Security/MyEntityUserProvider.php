<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class MyEntityUserProvider extends EntityUserProvider implements AccountConnectorInterface
{

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setterId = 'set' . ucfirst($serviceName) . 'ID';
        $setterAccessToken = 'set' . ucfirst($serviceName) . 'AccessToken';

        // unique integer
        $username = $response->getUsername();

        $email = $response->getEmail();
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null) {
            $user = new User();
            $user->setPseudo($response->getNickname())
                ->setEmail($email)
                // ->setAvatar($response->getProfilePicture())
                ->setEnable(true);
        }
        // $user = $this->findUser(array($this->properties[$resourceOwnerName] => $username));
        // TODO: Create the user

        $user->$setterId($username);
        $user->$setterAccessToken($response->getAccessToken());

        $this->em->persist($user);
        $this->em->flush();
        return $user;

        // JUST FOR Google
        //  $user->setGoogleAccessToken($response->getAccessToken());

        // return $user;
    }

    /**
     * Connects the response to the user object.
     *
     * @param UserInterface $user The user object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of App\Model\User, but got "%s".', get_class($user)));
        }

        $property = $this->getProperty($response);
        $username = $response->getUsername();

        if (null !== $previousUser = $this->registry->getRepository(User::class)->findOneBy(array($property => $username))) {
            // 'disconnect' previously connected users
            $this->disconnect($previousUser, $response);
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());

        $this->updateUser($user, $response);
    }

    /**
     * ##STOLEN#
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }
        return $this->properties[$resourceOwnerName];
    }

    /**
     * Disconnects a user.
     *
     * @param UserInterface $user
     * @param UserResponseInterface $response
     * @throws \TypeError
     */
    public function disconnect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($user, $property, null);

        $this->updateUser($user, $response);
    }

    /**
     * Update the user and persist the changes to the database.
     * @param UserInterface $user
     * @param UserResponseInterface $response
     */
    private function updateUser(UserInterface $user, UserResponseInterface $response)
    {
        $user->setEmail($response->getEmail())
            ->setAvatar($response->getProfilePicture())
            ->setPseudo($response->getNickname());

        $this->em->persist($user);
        $this->em->flush();
    }
}
