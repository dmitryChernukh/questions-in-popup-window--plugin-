<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SiteRepository")
 */
class Site
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="siteUrl", type="string", length=255)
     */
    private $siteUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="ipAddress", type="string", length=255, nullable=true)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="attachedElement", type="string", length=255, nullable=true)
     */
    private $attachedElement;


    /**
     * @var string
     *
     * @ORM\Column(name="appearance", type="string", length=255, nullable=true)
     */
    private $appearance;

    /**
     * @var string
     *
     * @ORM\Column(name="subsite", type="string", length=255, nullable=true)
     */
    private $subsite;

    /**
     * @var integer
     *
     * @ORM\Column(name="attachedPopupId", type="integer", length=5, nullable=true)
     */
    private $attachedPopupId;

    /**
     * @var string
     *
     * @ORM\Column(name="appValue", type="string", length=255, nullable=true)
     */
    private $appValue;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PopUp")
     *
     * @var AppBundle\Entity\PopUp;
     */
    protected $popUp;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question")
     *
     * @var AppBundle\Entity\Question;
     */
    protected $question;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="isSleep", type="boolean", nullable=true)
     */
    private $isSleep = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="protocol", type="string", length=255, nullable=true)
     */
    private $protocol;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PopUpPosition", inversedBy="site")
     *
     * @var AppBundle\Entity\PopUpPosition;
     */
    protected $popUpPosition;

    public function __construct()
    {

        $this->popUpPosition = new ArrayCollection();
        $this->question = new ArrayCollection();

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Site
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set protocol
     *
     * @param string $protocol
     *
     * @return Site
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * Get protocol
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set siteUrl
     *
     * @param string $siteUrl
     *
     * @return Site
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    //isSleep
    /**
     * Set isSleep
     *
     * @param string $isSleep
     *
     * @return Site
     */
    public function setIsSleep($isSleep)
    {
        $this->isSleep = $isSleep;

        return $this;
    }

    /**
     * Get isSleep
     *
     * @return string
     */
    public function getIsSleep()
    {
        return $this->isSleep;
    }

    /**
     * Get siteUrl
     *
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Site
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    //$attachedElement
    /**
     * Set attachedElement
     *
     * @param string $attachedElement
     *
     * @return Site
     */
    public function setAttachedElement($attachedElement)
    {
        $this->attachedElement = $attachedElement;
        return $this;
    }

    /**
     * Get attachedElement
     *
     * @return string
     */
    public function getAttachedElement()
    {
        return $this->attachedElement;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set popUp
     *
     * @param \AppBundle\Entity\PopUp $popUp
     * @return PopUp
     */
    public function setPopUp(\AppBundle\Entity\PopUp $popUp)
    {
        $this->popUp = $popUp;

        return $this;
    }

    /**
     * Get popUpId
     *
     * @return string
     */
    public function getPopUp()
    {
        return $this->popUp;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Question $question
     * @return Question
     */
    public function setQuestion(\AppBundle\Entity\Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Site
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Site
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Set attachedPopupId
     *
     * @param integer $attachedPopupId
     *
     * @return Site
     */
    public function setAttachedPopupId($attachedPopupId)
    {
        $this->attachedPopupId = $attachedPopupId;
        return $this;
    }

    /**
     * Get attachedPopupId
     *
     * @return integer
     */
    public function getAttachedPopupId()
    {
        return $this->attachedPopupId;
    }

    /**
     * Set Appearance
     *
     * @param string $appearance
     *
     * @return Site
     */
    public function setAppearance($appearance)
    {
        $this->appearance = $appearance;

        return $this;
    }

    //subsite

    /**
     * Set subsite
     *
     * @param string $subsite
     *
     * @return Site
     */
    public function setSubsite($subsite)
    {
        $this->subsite = $subsite;

        return $this;
    }

    /**
     * Get subsite
     *
     * @return string
     */
    public function getSubsite()
    {
        return $this->subsite;
    }

    /**
     * Get Appearance
     *
     * @return string
     */
    public function getAppearance()
    {
        return $this->appearance;
    }

    /**
     * Get appValue
     *
     * @return string
     */
    public function getAppValue()
    {
        return $this->appValue;
    }

    /**
     * Set AppValue
     *
     * @param string $appValue
     *
     * @return Site
     */
    public function setAppValue($appValue)
    {
        $this->appValue = $appValue;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set popUpPosition
     *
     * @param \AppBundle\Entity\PopUpPosition $popUpPosition
     * @return PopUpPosition
     */
    public function setPopUpPosition(\AppBundle\Entity\PopUpPosition $popUpPosition = null)
    {
        $this->popUpPosition = $popUpPosition;

        return $this;
    }

    /**
     * Get popUpPosition
     *
     * @return \AppBundle\Entity\PopUpPosition
     */
    public function getPopUpPosition()
    {
        return $this->popUpPosition;
    }
}

