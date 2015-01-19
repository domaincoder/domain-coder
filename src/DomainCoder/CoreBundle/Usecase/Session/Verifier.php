<?php
namespace DomainCoder\CoreBundle\Usecase\Session;

use DomainCoder\CoreBundle\Entity\Command\StartSessionCommand;
use DomainCoder\CoreBundle\Entity\Query\VerifySessionQuery;
use PHPMentors\DomainKata\Entity\EntityInterface;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class Verifier
 * @DI\Service("domaincoder.core.usecase.session.verifier")
 */
class Verifier
{
    /**
     * @param  EntityInterface $entity
     * @return string
     */
    public function generateVerifyString(EntityInterface $entity)
    {
        /** @var StartSessionCommand $entity */
        assert($entity instanceof StartSessionCommand);
        return substr(md5(rand()),-5) . sha1($entity->ip . $entity->ua);
    }

    /**
     * @param  EntityInterface $entity
     * @return bool
     */
    public function verify(EntityInterface $entity)
    {
        /** @var VerifySessionQuery $entity */
        assert($entity instanceof VerifySessionQuery);

        $expectedVerifier = $this->generateVerifyString($entity);

        return (substr($expectedVerifier, 5) == substr($entity->verifier, 5));
    }
}