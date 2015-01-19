<?php
namespace DomainCoder\CoreBundle\Entity\Query;

use DomainCoder\CoreBundle\Entity\Command\StartSessionCommand;
use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\Request;

class VerifySessionQuery extends StartSessionCommand
{
    /**
     * @var string
     */
    public $token;
    /**
     * @var string
     */
    public $verifier;

    public function __construct($token, $verifier, Request $request)
    {
        $this->token = $token;
        $this->verifier = $verifier;
        parent::__construct($request);
    }
}