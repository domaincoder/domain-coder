<?php
namespace DomainCoder\CoreBundle\Entity\Command;

use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\Request;

class StartSessionCommand implements EntityInterface
{
    /**
     * @var string
     */
    public $ua;
    /**
     * @var string
     */
    public $ip;
    /**
     * @var string
     */
    public $userName;

    public function __construct($userName, Request $request)
    {
        $this->userName = $userName;
        $this->ua = $request->headers->get('User-Agent');
        $this->ip = $request->getClientIp();
    }
}