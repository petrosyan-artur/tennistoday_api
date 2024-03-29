<?php
/**
 * Created by PhpStorm.
 * User: Knyazyan
 * Date: 9/29/15
 * Time: 3:21 PM
 */

namespace TTMainBundle\Entity\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use TTMainBundle\Entity\ClientUsers;

/**
 * ClientUsersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientUsersRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
        ;

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active admin AcmeUserBundle:User object identified by "%s".', $username), null, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function addNewCompany($username, $password, $companyName)
    {
        $em = $this->getEntityManager();
        $cu = new clientUsers();

        $cu->setUsername($username);
        $cu->setPassword($password);
        $cu->setCompanyName($companyName);

        $em->persist($cu);
        $em->flush();

        return true;
    }
}