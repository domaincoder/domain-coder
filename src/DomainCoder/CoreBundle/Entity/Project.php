<?php
namespace DomainCoder\CoreBundle\Entity;

use PHPMentors\DomainKata\Entity\EntityInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints As Assert;
use JMS\Serializer\Annotation As Serializer;

/**
 * Class Project
 * @package DomainCoder\CoreBundle
 * @Serializer\ExclusionPolicy("all")
 */
class Project implements EntityInterface, \JsonSerializable
{
    const DIR_PROJECT_INFO = '.domaincoder';
    const FILE_PROJECT_INFO = 'project_info.json';

    /**
     * @var string
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $name;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    private $path;
    /**
     * @var string
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $language;
    /**
     * @var string
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    public $generator;

    public function __construct($name = null, $path = null) {
        $this->name = ($name !== null) ? $name : '新規プロジェクト';

        if ($path === null) {$path = sys_get_temp_dir();}
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = rtrim($path, '/\\');
    }

    /**
     * @return bool
     */
    public function existsProjectDir()
    {
        $filesystem = new Filesystem();
        return $filesystem->exists($this->path);
    }

    /**
     * @return string
     */
    public function getProjectInfoDirPath()
    {
        return $this->path . DIRECTORY_SEPARATOR . self::DIR_PROJECT_INFO;
    }

    /**
     * @return bool
     */
    public function existsProjectInfoDir()
    {
        $filesystem = new Filesystem();
        return $filesystem->exists($this->getProjectInfoDirPath());
    }

    /**
     * @return string
     */
    public function getProjectInfoFilePath()
    {
        return $this->getProjectInfoDirPath() . DIRECTORY_SEPARATOR . self::FILE_PROJECT_INFO;
    }

    /**
     * @return bool
     */
    public function existsProjectInfoFile()
    {
        $filesystem = new Filesystem();
        return $filesystem->exists($this->getProjectInfoFilePath());
    }

    /**
     *
     */
    public function load()
    {
        $json = json_decode(file_get_contents($this->getProjectInfoFilePath()));

        $this->name = $json->name;
        $this->language = $json->language;
        $this->generator = $json->generator;
    }

    /**
     *
     */
    public function save()
    {
        file_put_contents($this->getProjectInfoFilePath(),
            json_encode($this));
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'language' => $this->language,
            'generator' => $this->generator
        ];
    }
}
