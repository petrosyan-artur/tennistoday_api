<?php

namespace TTMainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use TTMainBundle\Entity\Courts;
use Symfony\Component\Finder\Finder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $result = json_encode(array('success' => true));
        return new Response($result);
//        $em = $this->getDoctrine()->getManager();
//        $courts = $em->getRepository('TTMainBundle:Courts')->findAll();
//        $courtOffers = $em->getRepository('TTMainBundle:CourtOffers')->findAll();
////        var_dump($courts);exit;
//        return $this->render('TTMainBundle:Default:index.html.twig', array(
//            'courts' => $courts,
//            'courtOffers' => $courtOffers
//            )
//        );
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $finder = new Finder();

        $dir = $this->container->getParameter('symfony_dir');

        $files = glob($dir."web/uploads/court1/*.*");
        foreach ($files as $file) {
            echo basename($file).'<br>';
//            echo '<img src="/uploads/court1/carpet1.jpg"><br/>';
        }
        var_dump($files);exit;
        $startDate = '2015-09-29 17:36';
        $stopDate = '2015-09-29 18:36';
        $id = 3;

        $em = $this->getDoctrine()->getManager();
        $court = $em->getRepository('TTMainBundle:Courts')->findOneById($id);
        var_dump($court);exit;

        $addCourtOffer = $em->getRepository('TTMainBundle:CourtOffers')->addCourtOffer($court, $startDate, $stopDate, 5);
        var_dump($addCourtOffer);exit;
    }

    /**
     * @Route("/testFileUpload", name="test_file_upload")
     */
    public function testFileUploadAction()
    {
        $courts = new Courts();
        $form = $this->createFormBuilder($courts)
            ->add('companyName')
            ->add('courtName')
            ->add('companySubName')
            ->add('countryIsoCode')
            ->add('state')
            ->add('city')
            ->add('street')
            ->add('coverType', 'choice', array(
                'choices' => array(
                    'Clay' => 'clay',
                    'Grass' => 'grass',
                    'Carpet' => 'carpet',
                )
            ))
            ->add('surfaceType', 'choice', array(
                'choices' => array(
                    'Indoor' => 'indoor',
                    'Outdoor' => 'outdoor',
                )
            ))
            ->add('file')
            ->add('upload', 'submit')
            ->getForm();

//        var_dump($form);exit;
        $request = $this->get('request');
        $form->handleRequest($request);

        if ($form->isValid()) {
//            var_dump($form['file']->getData());exit;
            $em = $this->getDoctrine()->getManager();

            $courts->upload();

            $em->persist($courts);
            $em->flush();

            return $this->redirectToRoute('test_file_upload', array(), 301);
        }

        return $this->render('TTMainBundle:Default:test.html.twig', array(
                'form' => $form->createView(),
            ));
    }
}
