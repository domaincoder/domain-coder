<?php
namespace DomainCoder\CoreBundle\Usecase\Session;

use DomainCoder\CoreBundle\Entity\Query\VerifySessionQuery;
use DomainCoder\CoreBundle\Entity\Session;
use DomainCoder\CoreBundle\Repository\SessionRepository;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class GetCurrentUsecase
 * @DI\Service("domaincoder.core.usecase.session.get_current")
 */
class GetCurrentUsecase implements UsecaseInterface
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
        /** @var VerifySessionQuery $entity */
        assert($entity instanceof VerifySessionQuery);

        $session = $this->repository->get($entity->token);
        $entity->verifier = $session->tokenVerifiler;

        if (!$this->tokenVerifier->verify($entity)) {
            return null;
        }

        return $session;
    }
}