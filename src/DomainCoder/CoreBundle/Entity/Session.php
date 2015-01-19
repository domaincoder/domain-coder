<?php
namespace DomainCoder\CoreBundle\Entity;

use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\Validator\Constraints As Assert;
use JMS\Serializer\Annotation As Serializer;

/**
 * Class Session
 * @package DomainCoder\CoreBundle
 * @Serializer\ExclusionPolicy("all")
 */
class Session implements EntityInterface, \Serializable
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $token;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Serializer\Expose
     */
    public $userName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $tokenVerifiler;

    /**
     * @var Project
     */
    public $currentProject;

    /**
     * @param $token
     */
    public function __construct($token, $tokenVerifier = null)
    {
        $this->token = $token;
        $this->tokenVerifiler = $tokenVerifier;
    }

    /**
     * @param string
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'token' => $this->token,
            'userName' => $this->userName,
            'tokenVerifier' => $this->tokenVerifiler,
            'currentProject' => serialize($this->currentProject)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->token = $data['token'];
        $this->userName = $data['userName'];
        $this->tokenVerifiler = $data['tokenVerifier'];
        $this->currentProject = unserialize($data['currentProject']);
    }
}
