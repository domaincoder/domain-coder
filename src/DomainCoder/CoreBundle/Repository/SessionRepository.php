<?php
namespace DomainCoder\CoreBundle\Repository;

use DomainCoder\CoreBundle\Entity\Session;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class SessionRepository
 * @DI\Service("domaincoder.core.repository.session")
 */
class SessionRepository implements RepositoryInterface
{
    /**
     * @param EntityInterface $entity
     */
    public function add(EntityInterface $entity)
    {
        /** @var Session $entity */
        assert($entity instanceof Session);

        apc_add($entity->token, $entity);
        apc_add($entity->userName, $entity);
    }

    /**
     * @param $token
     * @return Session
     */
    public function get($token)
    {
        return apc_fetch($token);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findByName($name) {
        return apc_fetch($name);
    }


    /**
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity)
    {
        /** @var Session $entity */
        assert($entity instanceof Session);

        apc_delete($entity->token);
        apc_delete($entity->userName);
    }
}