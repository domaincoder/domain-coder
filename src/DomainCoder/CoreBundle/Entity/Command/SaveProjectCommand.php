<?php
namespace DomainCoder\CoreBundle\Entity\Command;

use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\Request;

class SaveProjectCommand implements EntityInterface
{
    public function __construct($token, Request $request)
    {

    }
}