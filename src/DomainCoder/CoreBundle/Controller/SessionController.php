<?php
namespace DomainCoder\CoreBundle\Controller;

use DomainCoder\CoreBundle\Entity\Command\StartSessionCommand;
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
 * @Annotations\Prefix("/session")
 * @Annotations\NamePrefix("core_session_")
 */
class SessionController extends FOSRestController
{
    /**
     * Start new session.
     *
     * @ApiDoc(
     *   output = "DomainCoder\CoreBundle\Entity\Session",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\Post("/start")
     * @Annotations\View(templateVar="session")
     *
     * @param Request $request the request object
     *
     * @return Session
     */
    public function postStartAction(Request $request)
    {
        if (!$request->request->has('name')) {
            throw $this->createNotFoundException('name parameter required');
        }
        $name = $request->request->get('name');
        $sessionStart = $this->get('domaincoder.core.usecase.session.start');
        $session = $sessionStart->run(new StartSessionCommand($name, $request));

        $view = new View($session);
        //$group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        //$view->getSerializationContext()->setGroups(array('Default', $group));
        return $view;
    }

    /**
     * Get current session.
     *
     * @ApiDoc(
     *   output = "DomainCoder\CoreBundle\Entity\Session",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no session found for specified token",
     *   }
     * )
     *
     * @Annotations\Get("/current")
     * @Annotations\View(templateVar="session")
     *
     * @param Request $request the request object
     * @param string  $token   the session token
     *
     * @return Session
     */
    public function getCurrentAction(Request $request)
    {
        $getCurrent = $this->get('domaincoder.core.usecase.session.get_current');
        $session = $getCurrent->run(new VerifySessionQuery($token, null, $request));

        $view = new View($session);
        //$group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        //$view->getSerializationContext()->setGroups(array('Default', $group));
        return $view;
    }
}