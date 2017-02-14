<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPopUp
 *
 * @ORM\Table(name="user_pop_up")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserPopUpRepository")
 */
class UserPopUp
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
     * @var integer
     *
     * @ORM\Column(name="popupId", type="integer", nullable=true)
     */
    private $popupId;


    /**
     * @var string
     *
     * @ORM\Column(name="popupName", type="string", length=255, nullable=true)
     */
    private $popupName;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalStatus", type="string", length=255, nullable=true)
     */
    private $additionalStatus;


    /**
     * @var string
     *
     * @ORM\Column(name="htmlCode", type="text", nullable=true)
     */
    private $htmlCode;

    /**
     * @var string
     *
     * @ORM\Column(name="cssCode", type="text", nullable=true)
     */
    private $cssCode;

    /**
     * @var string
     *
     * @ORM\Column(name="cssFile", type="text", nullable=true)
     */
    private $cssFile;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalInfo", type="string", length=255, nullable=true)
     */
    private $additionalInfo;

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
     * Set htmlCode
     *
     * @param string $htmlCode
     * @return UserPopUp
     */
    public function setHtmlCode($htmlCode)
    {
        $this->htmlCode = $htmlCode;

        return $this;
    }


    /**
     * Get popupId
     *
     * @return integer
     */
    public function getPopupId()
    {
        return $this->popupId;
    }

    /**
     * Set popupId
     *
     * @param integer $popupId
     * @return UserPopUp
     */
    public function setPopupId($popupId)
    {
        $this->popupId = $popupId;

        return $this;
    }

    /**
     * Get htmlCode
     *
     * @return string 
     */
    public function getHtmlCode()
    {
        return $this->htmlCode;
    }

    //cssFile

    /**
     * Get cssFile
     *
     * @return string
     */
    public function getСssFile()
    {
        return $this->cssFile;
    }

    /**
     * Set cssCode
     *
     * @param string $cssCode
     * @return UserPopUp
     */
    public function setCssCode($cssCode)
    {
        $this->cssCode = $cssCode;

        return $this;
    }

    /**
     * Set popupName
     *
     * @param string $popupName
     * @return UserPopUp
     */
    public function setPopupName($popupName)
    {
        $this->popupName = $popupName;

        return $this;
    }

    /**
     * Get popupName
     *
     * @return string
     */
    public function getPopupName()
    {
        return $this->popupName;
    }

    /**
     * Set cssFile
     *
     * @param string $cssFile
     * @return UserPopUp
     */
    public function setСssFile($cssFile)
    {
        $this->cssFile = $cssFile;

        return $this;
    }

    /**
     * Set additionalStatus
     *
     * @param string $additionalStatus
     * @return UserPopUp
     */
    public function setAdditionalStatus($additionalStatus)
    {
        $this->additionalStatus = $additionalStatus;

        return $this;
    }

    /**
     * Get additionalStatus
     *
     * @return string
     */
    public function getAdditionalStatus()
    {
        return $this->additionalStatus;
    }

    /**
     * Get cssCode
     *
     * @return string 
     */
    public function getCssCode()
    {
        return $this->cssCode;
    }

    /**
     * Set additionalInfo
     *
     * @param string $additionalInfo
     * @return UserPopUp
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    /**
     * Get additionalInfo
     *
     * @return string 
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }
}
