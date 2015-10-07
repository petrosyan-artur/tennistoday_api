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
 * @Route("/backend", name="backend")
 */
class PrivateController extends Controller
{
    /**
     * @Route("/private", name="backend_private")
     */
    public function PrivateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $companies =  $em->getRepository('TTMainBundle:ClientUsers')->findAll();

        return $this->render('TTMainBundle:Private:index.html.twig', array(
                'companies' => $companies
            )
        );
    }

    /**
     * @Route("/private/courtOffers", name="backend_private_court_offers")
     */
    public function courtOffersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $courtOffers = $em->getRepository('TTMainBundle:CourtOffers')->findAll(
            array('startDate' => 'DESC')
        );
        $statusList = array(0 => array('name' => 'active'), 1 => array('name' => 'booked'), 2 => array('name' => 'completed'), 3 => array('name' => 'inactive'), 4 => array('name' => 'outdated'));

        return $this->render('TTMainBundle:Private:offers.html.twig', array(
                'courtOffers' => $courtOffers,
                'statusList' => $statusList
            )
        );
    }

    /**
     * @Route("/private/updateCompany", name="backend_private_update_company")
     */
    public function updateCompanyAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $courts = $em->getRepository('TTMainBundle:Courts')->findBy(
//            array('companyName' => $this->getUser()->getCompanyName())
//        );

        return $this->render('TTMainBundle:Private:updateCompany.html.twig', array(
                'courts' => 1,
//                'courtOffers' => $courtOffers,
//                'statusList' => $statusList
            )
        );
    }
}
