<?php

namespace TTMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/request", name="request")
 */
class RequestController extends Controller
{
    private function checkHash($companyName, $hash) {
        $em = $this->getDoctrine()->getManager();
        $checkHash = $em->getRepository('TTMainBundle:ClientUsers')->findBy(array('companyName' => $companyName, 'hash' => $hash));
        if (!$checkHash) {
            return false;
        }
        return true;
    }
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

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash!'));
            return new Response($result);
        }

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

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash! '.$hash.' - '.$companyName));
            return new Response($result);
        }

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

    /**
     * @Route("/addNewCompany", name="add_new_company")
     */
    public function addNewCompanyAction()
    {
        $request = $this->get('request');
        $username = $request->get('username');
        $password = $request->get('password');
        $companyName = $request->get('companyName');

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash! '.$hash.' - '.$companyName));
            return new Response($result);
        }

        if (!$username or !$password or !$companyName) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();
        $checkCompanyName = $em->getRepository('TTMainBundle:ClientUsers')->findOneByCompanyName($companyName);
        if ($checkCompanyName) {
            $result = json_encode(array('success' => false, 'reason' => 'Duplicate name of company!'));
            return new Response($result);
        }

        $addNewCompany = $em->getRepository('TTMainBundle:ClientUsers')->addNewCompany($username, $password, $companyName);
        if (!$addNewCompany) {
            $result = json_encode(array('success' => false, 'reason' => 'Backend Problems!'));
            return new Response($result);
        }
        $result = json_encode(array('success' => true));
        return new Response($result);
    }

    /**
     * @Route("/updateCourts", name="update_courts")
     */
    public function updateCourtsAction()
    {
        $request = $this->get('request');
        $id = $request->get('id');
        $companySubName = $request->get('companySubName');
        $surfaceType = $request->get('surfaceType');
        $coverType = $request->get('coverType');
        $country = $request->get('country');
        $state = $request->get('state');
        $city = $request->get('city');
        $street = $request->get('street');

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash! '.$hash.' - '.$companyName));
            return new Response($result);
        }

        if (!$id or !$companySubName or !$surfaceType or !$coverType or !$country or !$state or !$city or !$street) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();

        $updateCourts = $em->getRepository('TTMainBundle:Courts')->updateCourts($id, $companySubName, $surfaceType, $coverType, $country, $state, $city, $street);
        if (!$updateCourts) {
            $result = json_encode(array('success' => false, 'reason' => 'Backend Problems!'));
            return new Response($result);
        }
        $result = json_encode(array('success' => true));
        return new Response($result);
    }

    /**
     * @Route("/addNewCourt", name="add_new_court")
     */
    public function addNewCourtAction()
    {
        $request = $this->get('request');
        $companySubName = $request->get('companySubName');
        $courtName = $request->get('courtName');
        $surfaceType = $request->get('surfaceType');
        $coverType = $request->get('coverType');
        $country = $request->get('country');
        $state = $request->get('state');
        $city = $request->get('city');
        $street = $request->get('street');

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash! '.$hash.' - '.$companyName));
            return new Response($result);
        }

        if (!$courtName or !$companySubName or !$surfaceType or !$coverType) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();

        if (!$country or !$state or !$city or !$street) {
            $sub = $em->getRepository('TTMainBundle:SubCompanies')->findOneByCompanySubName($companySubName);
            $country = $sub->getCountryIsoCode();
            $state = $sub->getState();
            $city = $sub->getCity();
            $street = $sub->getStreet();
        }

        $addNewCourt = $em->getRepository('TTMainBundle:Courts')->addNewCourt($companyName, $companySubName, $courtName, $surfaceType, $coverType, $country, $state, $city, $street);
        if (!$addNewCourt) {
            $result = json_encode(array('success' => false, 'reason' => 'Court with such name already exists!'));
            return new Response($result);
        }

        $fs = new Filesystem();
        $dir = $this->container->getParameter('symfony_dir');
        try {
            $fs->mkdir($dir.'images/'.str_replace(' ','', $courtName));
        } catch (IOExceptionInterface $e) {
            $error = "An error occurred while creating your directory at ".$e->getPath();
            $result = json_encode(array('success' => false, 'reason' => $error));
            return new Response($result);
        }

        $result = json_encode(array('success' => true));
        return new Response($result);
    }

    /**
     * @Route("/addNewSubCompany", name="add_new_sub_company")
     */
    public function addNewSubCompanyAction()
    {
        $request = $this->get('request');
        $companySubName = $request->get('companySubName');
        $country = $request->get('country');
        $state = $request->get('state');
        $city = $request->get('city');
        $street = $request->get('street');
        $phoneNumber = $request->get('phoneNumber');

        $hash = $request->get('hash');
        $companyName = $request->get('companyName');

        if (!$this->checkHash($companyName, $hash)) {
            $result = json_encode(array('success' => false, 'reason' => 'Invalid hash! '.$hash.' - '.$companyName));
            return new Response($result);
        }

        if (!$phoneNumber or !$companySubName or !$country or !$state or !$city or !$street) {
            $result = json_encode(array('success' => false, 'reason' => 'Fields are empty!'));
            return new Response($result);
        }

        $em = $this->getDoctrine()->getManager();

        $addNewSubCompany = $em->getRepository('TTMainBundle:SubCompanies')->addNewSubCompany($companyName, $companySubName, $country, $state, $city, $street, $phoneNumber);
        if (!$addNewSubCompany) {
            $result = json_encode(array('success' => false, 'reason' => 'SubCompany with such name already exists!'));
            return new Response($result);
        }
        $result = json_encode(array('success' => true));
        return new Response($result);
    }

    /**
     * @Route("/fileUpload", name="file_upload")
     */
    public function fileUploadAction()
    {
        $request = $this->get('request');
        $courtName = $request->get('court_modal_name');
        $allowed = array('png', 'jpg', 'gif','zip');

        if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

            $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

            if(!in_array(strtolower($extension), $allowed)){
                echo '{"status":"error"}';
                exit;
            }

            $fs = new Filesystem();
            $dir = $this->container->getParameter('symfony_dir');

            if(move_uploaded_file($_FILES['upl']['tmp_name'], $dir.'web/uploads/'.$courtName.'/'.$_FILES['upl']['name'])){
                echo '{"status":"success"}';
                exit;
            }
        }

        echo '{"status":"error"}';
    }

    /**
     * @Route("/getCourtImages", name="get_court_images")
     */

    public function getCourtImagesAction()
    {
        $request = $this->get('request');
        $courtName = $request->get('courtName');

        $dir = $this->container->getParameter('symfony_dir');

        $files = glob($dir."web/uploads/".$courtName."/*.*");

        if (!$files) {
            $result = json_encode(array('success' => false));
            return new Response($result);
        }

        foreach ($files as $file) {
            $images[] = basename($file);
        }

        $result = json_encode(array('success' => true, 'images' => $images));
        return new Response($result);
    }

    /**
     * @Route("/removeCourtImage", name="remove_court_image")
     */

    public function removeCourtImageAction()
    {
        $request = $this->get('request');
        $courtName = $request->get('courtName');
        $imageName = $request->get('imageName');

        $dir = $this->container->getParameter('symfony_dir');

        if ($courtName && $imageName) {
            $unlink = unlink($dir."web/uploads/".$courtName."/".$imageName);
        }

        $files = glob($dir."web/uploads/".$courtName."/*.*");

        if (!$files) {
            $result = json_encode(array('success' => false));
            return new Response($result);
        }

        foreach ($files as $file) {
            $images[] = basename($file);
        }

        $result = json_encode(array('success' => true, 'images' => $images));
        return new Response($result);
    }
}
