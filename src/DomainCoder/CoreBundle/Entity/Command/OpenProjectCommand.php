<?php
namespace DomainCoder\CoreBundle\Entity\Command;

use PHPMentors\DomainKata\Entity\EntityInterface;

class OpenProjectCommand implements EntityInterface
{
    public $token;
    public $path;
    public function __construct($token, $path)
    {
        $this->token = $token;
        $this->path = $path;
    }
}