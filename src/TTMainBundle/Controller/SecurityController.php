<?php

namespace TTMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/", name="clients_backend")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'TTMainBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/logout", name="logout_route")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/clientBackend", name="client_backend")
     */
    public function clientBackendAction()
    {
        if (!$this->getUser()) {
            return $this->redirect($this->generateUrl('login'));
        }
        $em = $this->getDoctrine()->getManager();
        $courts = $em->getRepository('TTMainBundle:Courts')->findBy(
            array('companyName' => $this->getUser()->getCompanyName())
        );
        $courtOffers = $em->getRepository('TTMainBundle:CourtOffers')->findBy(
            array('companyName' => $this->getUser()->getCompanyName()),
            array('startDate' => 'DESC')
        );
        $statusList = array(0 => array('name' => 'active'), 1 => array('name' => 'booked'), 2 => array('name' => 'completed'), 3 => array('name' => 'inactive'), 4 => array('name' => 'outdated'));

        return $this->render('TTMainBundle:Security:index.html.twig', array(
                'courts' => $courts,
                'courtOffers' => $courtOffers,
                'statusList' => $statusList
            )
        );
    }
}
