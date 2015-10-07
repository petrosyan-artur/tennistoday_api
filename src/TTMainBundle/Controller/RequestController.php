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
 * @Route("/request", name="request")
 */
class RequestController extends Controller
{
    /**
     * @Route("/addCourtOffer", name="add_court_offer")
     */
    public function addCourtOfferAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $startDate = $request->get('startDate');
        $stopDate = $request->get('stopDate');
        $price = $request->get('price');

        if (!$id or !$startDate or !$stopDate or !$price ) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }
        $start = strtotime($startDate);
        $stop = strtotime($stopDate);

        if ((($stop - $start)/60 > 300) || (($stop - $start) <= 0)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid dates difference!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();
        $checkCourtOffer = $em->getRepository('TTMainBundle:CourtOffers')->checkCourtOffers($id, $startDate, $stopDate);
        if (!$checkCourtOffer) {
            $result = json_encode(array('success' => false, 'reason' => 'Duplicate order!'));
            return new Response($result);
        }
        $court = $em->getRepository('TTMainBundle:Courts')->findOneById($id);
        $addCourtOffer = $em->getRepository('TTMainBundle:CourtOffers')->addCourtOffer($court, $startDate, $stopDate, $price);
        if (!$addCourtOffer) {
            $result = json_encode(array('success' => false, 'reason' => 'Backend Problems!'));
            return new Response($result);
        }
        $result = json_encode(array('success' => true));
        return new Response($result);
    }

    /**
     * @Route("/updateCourtOffer", name="update_court_offer")
     */
    public function updateCourtOfferAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $startDate = $request->get('startDate');
        $stopDate = $request->get('stopDate');
        $price = $request->get('price');
        $status = $request->get('status');

        if (!$id or !$startDate or !$stopDate or !$price ) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }
        $start = strtotime($startDate);
        $stop = strtotime($stopDate);

        if ((($stop - $start)/60 > 300) || (($stop - $start) <= 0)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid dates difference!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();
        $checkCourtOffer = $em->getRepository('TTMainBundle:CourtOffers')->checkCourtOffers($id, $startDate, $stopDate);
        if (!$checkCourtOffer) {
            $result = json_encode(array('success' => false, 'reason' => 'Duplicate order!'));
            return new Response($result);
        }
        $updateCourtOffer = $em->getRepository('TTMainBundle:CourtOffers')->updateCourtOffer($id, $startDate, $stopDate, $price, $status);
        if (!$updateCourtOffer) {
            $result = json_encode(array('success' => false, 'reason' => 'Backend Problems!'));
            return new Response($result);
        }
        $result = json_encode(array('success' => true));
        return new Response($result);
    }
}
