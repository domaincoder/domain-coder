<?php
namespace DomainCoder\CoreBundle\Usecase\Session;

use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class TokenGenerator
 * @DI\Service("domaincoder.core.usecase.session.token_generator")
 */
class TokenGenerator
{
    /**
     * @var SecureRandomInterface
     * @DI\Inject("security.secure_random")
     */
    public $secureRandom;

    /**
     * @return string
     */
    public function generate()
    {
        return base64_encode($this->secureRandom->nextBytes(24));
    }
}