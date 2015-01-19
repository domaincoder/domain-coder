<?php
namespace DomainCoder\CoreBundle\Usecase\Project;

use DomainCoder\CoreBundle\Entity\Project;
use Symfony\Component\Filesystem\Filesystem;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use DomainCoder\CoreBundle\Entity\Command\OpenProjectCommand;

use JMS\DiExtraBundle\Annotation As DI;

/**
 * Class OpenUsecase
 * @DI\Service("domaincoder.core.usecase.project.open")
 */
class OpenUsecase implements UsecaseInterface
{
    /**
     * @var Filesystem
     * @DI\Inject("filesystem")
     */
    public $filesystem;

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity)
    {
        /** @var OpenProjectCommand $entity */
        assert($entity instanceof OpenProjectCommand);
        if (!$this->filesystem->exists($entity->path)) return null;

        $project = new Project(null, $entity->path);

        // if info dir/file doesn't exist, let's create
        if (!$project->existsProjectInfoDir()) {
            $this->filesystem->mkdir($project->getProjectInfoDirPath());
        };
        if (!$project->existsProjectInfoFile()) {
            $this->filesystem->touch($project->getProjectInfoFilePath());
        };

        $project->load();

        return $project;
    }
}