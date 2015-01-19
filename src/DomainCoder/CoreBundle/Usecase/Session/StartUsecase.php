<?php
namespace DomainCoder\CoreBundle\Usecase\Session;

use DomainCoder\CoreBundle\Entity\Command\StartSessionCommand;
use DomainCoder\CoreBundle\Entity\Session;
use DomainCoder\CoreBundle\Repository\SessionRepository;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class StartUsecase
 * @DI\Service("domaincoder.core.usecase.session.start")
 */
class StartUsecase implements UsecaseInterface
{
    /**
     * @var TokenGenerator
     * @DI\Inject("domaincoder.core.usecase.session.token_generator")
     */
    public $tokenGenerator;

    /**
     * @var Verifier
     * @DI\Inject("domaincoder.core.usecase.session.verifier")
     */
    public $tokenVerifier;

    /**
     * @var SessionRepository
     * @DI\Inject("domaincoder.core.repository.session")
     */
    public $repository;

    /**
     * @param  EntityInterface $entity
     * @return string
     */
    public function run(EntityInterface $entity)
    {
        /** @var StartSessionCommand $entity */
        assert($entity instanceof StartSessionCommand);

        $existing = $this->repository->findByName($entity->userName);

        $token = $this->tokenGenerator->generate();
        $verifier = $this->tokenVerifier->generateVerifyString($entity);

        /** @var Session $session */
        if ($existing) {
            $session = clone $existing;

            // removes old session (by token)
            $this->repository->remove($existing);

            $session = $existing;
            $session->setToken($token);
            $session->tokenVerifiler = $verifier;
        } else {
            $session = new Session($token, $verifier);
            $session->userName = $entity->userName;
        }
        $this->repository->add($session);

        return $session;
    }
}