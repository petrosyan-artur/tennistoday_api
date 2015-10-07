<?php

namespace TTMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Courts
 *
 * @ORM\Table(name="courts")
 * @ORM\Entity(repositoryClass="TTMainBundle\Entity\Repository\CourtsRepository")
 */
class Courts
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=50, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="court_name", type="string", length=50, nullable=true)
     */
    private $courtName;

    /**
     * @var string
     *
     * @ORM\Column(name="company_sub_name", type="string", length=50, nullable=true)
     */
    private $companySubName;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso_code", type="string", length=2, nullable=true)
     */
    private $countryIsoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=50, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_type", type="string", nullable=true)
     */
    private $coverType;

    /**
     * @var string
     *
     * @ORM\Column(name="surface_type", type="string", nullable=true)
     */
    private $surfaceType;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    public $filePath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Courts
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set courtName
     *
     * @param string $courtName
     *
     * @return Courts
     */
    public function setCourtName($courtName)
    {
        $this->courtName = $courtName;

        return $this;
    }

    /**
     * Get courtName
     *
     * @return string
     */
    public function getCourtName()
    {
        return $this->courtName;
    }

    /**
     * Set companySubName
     *
     * @param string $companySubName
     *
     * @return Courts
     */
    public function setCompanySubName($companySubName)
    {
        $this->companySubName = $companySubName;

        return $this;
    }

    /**
     * Get companySubName
     *
     * @return string
     */
    public function getCompanySubName()
    {
        return $this->companySubName;
    }

    /**
     * Set countryIsoCode
     *
     * @param string $countryIsoCode
     *
     * @return Courts
     */
    public function setCountryIsoCode($countryIsoCode)
    {
        $this->countryIsoCode = $countryIsoCode;

        return $this;
    }

    /**
     * Get countryIsoCode
     *
     * @return string
     */
    public function getCountryIsoCode()
    {
        return $this->countryIsoCode;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Courts
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Courts
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Courts
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set coverType
     *
     * @param string $coverType
     *
     * @return Courts
     */
    public function setCoverType($coverType)
    {
        $this->coverType = $coverType;

        return $this;
    }

    /**
     * Get coverType
     *
     * @return string
     */
    public function getCoverType()
    {
        return $this->coverType;
    }

    /**
     * Set surfaceType
     *
     * @param string $surfaceType
     *
     * @return Courts
     */
    public function setSurfaceType($surfaceType)
    {
        $this->surfaceType = $surfaceType;

        return $this;
    }

    /**
     * Get surfaceType
     *
     * @return string
     */
    public function getSurfaceType()
    {
        return $this->surfaceType;
    }

    public function getAbsolutePath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadRootDir().'/'.$this->filePath;
    }

    public function getWebPath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadDir().'/'.$this->filePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->filePath = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}
