<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdditionalDisplayConditions
 *
 * @ORM\Table(name="additional_display_conditions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdditionalDisplayConditionsRepository")
 */
class AdditionalDisplayConditions
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="certainDevice", type="string", length=255, nullable=true)
     */
    private $certainDevice;

    /**
     * @var bool
     *
     * @ORM\Column(name="singlVisit", type="boolean", nullable=true)
     */
    private $singlVisit;

    /**
     * @var int
     *
     * @ORM\Column(name="siteId", type="integer", length=4)
     */
    private $siteId;


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
     * Set url
     *
     * @param string $url
     * @return AdditionalDisplayConditions
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set certainDevice
     *
     * @param string $certainDevice
     * @return AdditionalDisplayConditions
     */
    public function setCertainDevice($certainDevice)
    {
        $this->certainDevice = $certainDevice;

        return $this;
    }

    /**
     * Get certainDevice
     *
     * @return string 
     */
    public function getCertainDevice()
    {
        return $this->certainDevice;
    }

    /**
     * Set singlVisit
     *
     * @param boolean $singlVisit
     * @return AdditionalDisplayConditions
     */
    public function setSinglVisit($singlVisit)
    {
        $this->singlVisit = $singlVisit;

        return $this;
    }

    /**
     * Get singlVisit
     *
     * @return boolean 
     */
    public function getSinglVisit()
    {
        return $this->singlVisit;
    }

    /**
     * Set siteId
     *
     * @param integer $siteId
     * @return AdditionalDisplayConditions
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return integer 
     */
    public function getSiteId()
    {
        return $this->siteId;
    }
}
