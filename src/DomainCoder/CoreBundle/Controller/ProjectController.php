<?php
namespace DomainCoder\CoreBundle\Controller;

use DomainCoder\CoreBundle\Entity\Command\OpenProjectCommand;
use DomainCoder\CoreBundle\Entity\Project;
use DomainCoder\CoreBundle\Entity\Query\VerifySessionQuery;
use DomainCoder\CoreBundle\Entity\Session;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Annotations\Prefix("/project")
 * @Annotations\NamePrefix("core_project_")
 */
class ProjectController extends FOSRestController
{
    /**
     * @param $token
     * @param $request
     * @return Session|null
     */
    private function getCurrentSession($token, $request) {
        $getCurrent = $this->get('domaincoder.core.usecase.session.get_current');

        return $getCurrent->run(new VerifySessionQuery($token, null, $request));
    }

    /**
     * Get a project info.
     *
     * @ApiDoc(
     *   output = "DomainCoder\CoreBundle\Entity\Project",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\Get("/project")
     * @Annotations\View(templateVar="project")
     *
     * @param Request $request the request object
     * @param string  $token
     *
     * @return Project
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function getProjectAction(Request $request)
    {
        if (!$session->currentProject) {
            throw $this->createNotFoundException("No project opened.");
        }
        $project = $session->currentProject;

        $view = new View($project);
        //$group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        //$view->getSerializationContext()->setGroups(array('Default', $group));
        return $view;
    }

    /**
     * Get a project info.
     *
     * @ApiDoc(
     *   output = "DomainCoder\CoreBundle\Entity\Project",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\Post("/open")
     * @Annotations\View(templateVar="project")
     *
     * @param Request $request the request object
     * @param string  $token
     *
     * @return Project
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function postOpenAction(Request $request)
    {
        if (!$request->request->has('path')) {
            throw $this->createNotFoundException('path parameter required');
        }
        $path = $request->request->get('path');

        $openProjectCommand = new OpenProjectCommand($token, $path);
        $projectOpenUsecase = $this->get('domaincoder.core.usecase.project.open');

        $project = $projectOpenUsecase->run($openProjectCommand);
        if ($project == null) {
            throw $this->createNotFoundException("Project does not exist.");
        }

        $view = new View($project);
        //$group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        //$view->getSerializationContext()->setGroups(array('Default', $group));
        return $view;
    }
}