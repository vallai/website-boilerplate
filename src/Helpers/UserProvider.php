<?php

namespace Sources\Helpers;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Sources\Models\Utilisateur;


/**
 * Source : http://www.bubblecode.net/fr/2012/08/28/authentification-mysql-avec-silex-le-micro-framework-php/
 */
class UserProvider implements UserProviderInterface
{

	public function __construct()
	{
	}

	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Security\Core\User\UserProviderInterface::loadUserByUsername()
	 */
	public function loadUserByUsername($username)
	{
        return Utilisateur::loadUserByUsername($username);
	}

	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Security\Core\User\UserProviderInterface::refreshUser()
	 */
	public function refreshUser(UserInterface $user)
	{
	    $class = get_class($user);

		if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
	}

	public function supportsClass($class)
	{
		return $class === 'Sources\Models\Utilisateur';
	}
}
