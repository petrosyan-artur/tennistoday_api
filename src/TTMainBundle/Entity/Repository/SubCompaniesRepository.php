<?php
/**
 * Created by PhpStorm.
 * User: Knyazyan
 * Date: 9/29/15
 * Time: 3:21 PM
 */

namespace TTMainBundle\Entity\Repository;

use TTMainBundle\Entity\SubCompanies;
use Doctrine\ORM\EntityRepository;

/**
 * SubCompaniesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubCompaniesRepository extends EntityRepository
{
    public function addNewSubCompany($companyName, $companySubName, $country, $state, $city, $street, $phoneNumber)
    {
        $em = $this->getEntityManager();
        $sc = $em->getRepository('TTMainBundle:SubCompanies')->findOneByCompanySubName($companySubName);

        if ($sc) {
            return false;
        }

        $sub = new SubCompanies();

        $sub->setCompanyName($companyName);
        $sub->setCompanySubName($companySubName);
        $sub->setCountryIsoCode($country);
        $sub->setState($state);
        $sub->setCity($city);
        $sub->setStreet($street);
        $sub->setPhoneNumber($phoneNumber);

        $em->persist($sub);
        $em->flush();

        return true;
    }
}