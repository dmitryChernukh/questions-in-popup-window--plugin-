<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * PopUp
 *
 * @ORM\Table(name="pop_up")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PopUpRepository")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class PopUp
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
     * @var int
     *
     * @ORM\Column(name="height", type="integer", length=5)
     */
    private $height;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BorderStyle", inversedBy="popUp")
     *
     * @var AppBundle\Entity\BorderStyle;
     */
    protected $borderStyle;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Site", mappedBy="Site")
     */
    protected $site;


    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", length=5)
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="bgColor", type="string", length=100)
     */
    private $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonColor", type="string", length=100, nullable=true)
     */
    private $buttonColor;

    /**
     * @var int
     *
     * @ORM\Column(name="buttonSpace", type="integer", length=5, nullable=true)
     */
    private $buttonSpace;

    /**
     * @var string
     *
     * @ORM\Column(name="textColor", type="string", length=255, nullable=true)
     */
    private $textColor;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonColorBorder", type="string", length=255, nullable=true)
     */
    private $buttonColorBorder;


    //Button border radius
    /**
     * @var int
     *
     * @ORM\Column(name="buttonBorderRadius", type="integer", length=5, nullable=true)
     */
    private $buttonBorderRadius;

    //buttonTextAlign
    /**
     * @var string
     *
     * @ORM\Column(name="buttonTextAlign", type="string", length=255, nullable=true)
     */
    private $buttonTextAlign;

    /**
     * @var int
     *
     * @ORM\Column(name="borderRadius", type="integer", length=5, nullable=true)
     */
    private $borderRadius;

    /**
     * @var string
     *
     * @ORM\Column(name="borderColour", type="string", length=255, nullable=true)
     */
    private $borderColour;

    /**
     * @var string
     *
     * @ORM\Column(name="borderWidth", type="string", length=255, nullable=true)
     */
    private $borderWidth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="archive", type="boolean", length=2, nullable=true)
     */
    private $archive;

    /**
     * @var string
     *
     * @ORM\Column(name="mainQuestionTextSize", type="string", length=255, nullable=true)
     */
    private $mainQuestionTextSize;

    /**
     * @var string
     *
     * @ORM\Column(name="answersTextSize", type="string", length=255, nullable=true)
     */
    private $answersTextSize;


    /**
     * @var string
     *
     * @ORM\Column(name="answersContainerMarginTop", type="string", length=255, nullable=true)
     */
    private $answersContainerMarginTop;


    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(name="updated", type="integer", length=11)
     */
    private $update;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $buttonTextColor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imageHashName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $originalImageName;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;


    //------ add new values
    //work with picture
    /**
     * @var "string"
     *
     * @ORM\Column(name="pictureWidth", type="string", length=255, nullable=true)
     */
    private $pictureWidth = 100;

    /**
     * @var "string"
     *
     * @ORM\Column(name="pictureTopMargin", type="string", length=255, nullable=true)
     */
    private $pictureTopMargin = 5;

    /**
     * @var "string"
     *
     * @ORM\Column(name="pictureBorderRadius", type="string", length=255, nullable=true)
     */
    private $pictureBorderRadius = 0;

    /**
     * @var "string"
     *
     * @ORM\Column(name="pictureOpacity", type="string", length=255, nullable=true)
     */
    private $pictureOpacity = 1;

    //------------
    //work with main text

    /**
     * @var "string"
     *
     * @ORM\Column(name="lineHeight", type="string", length=255, nullable=true)
     */
    private $lineHeight = '1.5';

    /**
     * @var "string"
     *
     * @ORM\Column(name="textTopMargin", type="string", length=255, nullable=true)
     */
    private $textTopMargin = 20;

    /**
     * @var "string"
     *
     * @ORM\Column(name="letterSpacing", type="string", length=255, nullable=true)
     */
    private $letterSpacing = 0;

    /**
     * @var "string"
     *
     * @ORM\Column(name="fontStyle", type="string", length=255, nullable=true)
     */
    private $fontStyle = 'normal';

    /**
     * @var "string"
     *
     * @ORM\Column(name="fontWeight", type="string", length=255, nullable=true)
     */
    private $fontWeight = 500;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->borderStyle = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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
     * @return PopUp
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set position
     *
     * @param string $position
     *
     * @return PopUp
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Set pictureWidth
     *
     * @param string $pictureWidth
     *
     * @return PopUp
     */
    public function setPictureWidth($pictureWidth)
    {
        $this->pictureWidth = $pictureWidth;

        return $this;
    }

    /**
     * Get pictureWidth
     *
     * @return string
     */
    public function getPictureWidth()
    {
        return $this->pictureWidth;
    }

    //buttonBorderRadius
    /**
     * Get buttonBorderRadius
     *
     * @return integer
     */
    public function getButtonBorderRadius()
    {
        return $this->buttonBorderRadius;
    }

    /**
     * Set buttonBorderRadius
     *
     * @param integer $buttonBorderRadius
     *
     * @return PopUp
     */
    public function setButtonBorderRadius($buttonBorderRadius)
    {
        $this->buttonBorderRadius = $buttonBorderRadius;

        return $this;
    }

    //buttonTextAlign
    /**
     * Set buttonTextAlign
     *
     * @param string $buttonTextAlign
     *
     * @return PopUp
     */
    public function setButtonTextAlign($buttonTextAlign)
    {
        $this->buttonTextAlign = $buttonTextAlign;

        return $this;
    }

    /**
     * Get buttonTextAlign
     *
     * @return string
     */
    public function getButtonTextAlign()
    {
        return $this->buttonTextAlign;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set pictureBorderRadius
     *
     * @param string $pictureBorderRadius
     *
     * @return PopUp
     */
    public function setPictureBorderRadius($pictureBorderRadius)
    {
        $this->pictureBorderRadius = $pictureBorderRadius;

        return $this;
    }

    //$answersContainerMarginTop
    /**
     * Set answersContainerMarginTop
     *
     * @param string $answersContainerMarginTop
     *
     * @return PopUp
     */
    public function setAnswersContainerMarginTop($answersContainerMarginTop)
    {
        $this->answersContainerMarginTop = $answersContainerMarginTop;

        return $this;
    }

    /**
     * Get answersContainerMarginTop
     *
     * @return string
     */
    public function getAnswersContainerMarginTop()
    {
        return $this->answersContainerMarginTop;
    }

    /**
     * Set textTopMargin
     *
     * @param string $textTopMargin
     *
     * @return PopUp
     */
    public function setTextTopMargin($textTopMargin)
    {
        $this->textTopMargin = $textTopMargin;

        return $this;
    }

    /**
     * Get textTopMargin
     *
     * @return string
     */
    public function getTextTopMargin()
    {
        return $this->textTopMargin;
    }

    //$letterSpacing
    /**
     * Get letterSpacing
     *
     * @return string
     */
    public function getLetterSpacing()
    {
        return $this->letterSpacing;
    }

    /**
     * Set letterSpacing
     *
     * @param string $letterSpacing
     *
     * @return PopUp
     */
    public function setLetterSpacing($letterSpacing)
    {
        $this->letterSpacing = $letterSpacing;

        return $this;
    }

    //buttonColorBorder
    /**
     * Set buttonColorBorder
     *
     * @param string $buttonColorBorder
     *
     * @return PopUp
     */
    public function setButtonColorBorder($buttonColorBorder)
    {
        $this->buttonColorBorder = $buttonColorBorder;

        return $this;
    }

    /**
     * Get buttonColorBorder
     *
     * @return string
     */
    public function getButtonColorBorder()
    {
        return $this->buttonColorBorder;
    }

    //$fontStyle
    /**
     * Get fontStyle
     *
     * @return string
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Set fontStyle
     *
     * @param string $fontStyle
     *
     * @return PopUp
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle = $fontStyle;

        return $this;
    }

    //$fontWeight
    /**
     * Get fontWeight
     *
     * @return string
     */
    public function getFontWeight()
    {
        return $this->fontWeight;
    }

    /**
     * Set fontWeight
     *
     * @param string $fontWeight
     *
     * @return PopUp
     */
    public function setFontWeight($fontWeight)
    {
        $this->fontWeight = $fontWeight;

        return $this;
    }

    /**
     * Set pictureOpacity
     *
     * @param string $pictureOpacity
     *
     * @return PopUp
     */
    public function setPictureOpacity($pictureOpacity)
    {
        $this->pictureOpacity = $pictureOpacity;

        return $this;
    }

    /**
     * Get pictureOpacity
     *
     * @return string
     */
    public function getPictureOpacity()
    {
        return $this->pictureOpacity;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPictureBorderRadius()
    {
        return $this->pictureBorderRadius;
    }

    /**
     * Get lineHeight
     *
     * @return string
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Set lineHeight
     *
     * @param string $lineHeight
     *
     * @return PopUp
     */
    public function setLineHeight($lineHeight)
    {
        $this->lineHeight = $lineHeight;

        return $this;
    }

    /**
     * Set pictureTopMargin
     *
     * @param string $pictureTopMargin
     *
     * @return PopUp
     */
    public function setPictureTopMargin($pictureTopMargin)
    {
        $this->pictureTopMargin = $pictureTopMargin;

        return $this;
    }

    /**
     * Get pictureTopMargin
     *
     * @return string
     */
    public function getPictureTopMargin()
    {
        return $this->pictureTopMargin;
    }


    /**
     * Set height
     *
     * @param integer $height
     *
     * @return PopUp
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Set buttonTextColor
     *
     * @param string $buttonTextColor
     *
     * @return PopUp
     */
    public function setButtonTextColor($buttonTextColor)
    {
        $this->buttonTextColor = $buttonTextColor;
        return $this;
    }

    /**
     * Get buttonTextColor
     *
     * @return string
     */
    public function getButtonTextColor()
    {
        return $this->buttonTextColor;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get imageHashName
     *
     * @return string
     */
    public function getImageHashName()
    {
        return $this->imageHashName;
    }

    /**
     * Set imageHashName
     *
     * @param string $imageHashName
     *
     * @return PopUp
     */
    public function setImageHashName($imageHashName)
    {
        $this->imageHashName = $imageHashName;

        return $this;
    }

    /**
     * Set buttonSpace
     *
     * @param integer $buttonSpace
     *
     * @return PopUp
     */
    public function setButtonSpace($buttonSpace)
    {
        $this->buttonSpace = $buttonSpace;

        return $this;
    }

    /**
     * Get buttonSpace
     *
     * @return integer
     */
    public function getButtonSpace()
    {
        return $this->buttonSpace;
    }

    /**
     * Get originalImageName
     *
     * @return string
     */
    public function getOriginalImageName()
    {
        return $this->originalImageName;
    }

    /**
     * Set originalImageName
     *
     * @param string $originalImageName
     *
     * @return PopUp
     */
    public function setOriginalImageName($originalImageName)
    {
        $this->originalImageName = $originalImageName;

        return $this;
    }

    /**
     * Get update
     *
     * @return int
     */
    public function getUpdated()
    {
        if(empty($this->update)){
            $this->update = 0;
        }
        return $this->update;
    }

    /**
     * Set update
     *
     * @param integer $update
     *
     * @return PopUp
     */
    public function setUpdated($update)
    {
        $this->update = time();

        return $this;
    }


    /**
     * Set update
     *
     * @param string $borderRadius
     *
     * @return PopUp
     */
    public function setBorderRadius($borderRadius)
    {
        $this->borderRadius = $borderRadius;

        return $this;
    }

    /**
     * Get update
     *
     * @return int
     */
    public function getBorderRadius()
    {
        return $this->borderRadius;
    }


    /**
     * Get borderColour
     *
     * @return string $borderColour
     */
    public function getBorderColour()
    {
        return $this->borderColour;
    }

    /**
     * Set borderColour
     *
     * @param string $borderColour
     *
     * @return PopUp
     */
    public function setBorderColour($borderColour)
    {
        $this->borderColour = $borderColour;

        return $this;
    }

    /**
     * Set borderWidth
     *
     * @param string $borderWidth
     *
     * @return PopUp
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    //$archive

    /**
     * Set archive
     *
     * @param string $archive
     *
     * @return PopUp
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean $archive
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Get borderWidth
     *
     * @return string $borderWidth
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }

    /**
     * Set mainQuestionTextSize
     *
     * @param string $mainQuestionTextSize
     *
     * @return PopUp
     */
    public function setMainQuestionTextSize($mainQuestionTextSize)
    {
        $this->mainQuestionTextSize = $mainQuestionTextSize;

        return $this;
    }

    /**
     * Get mainQuestionTextSize
     *
     * @return string $mainQuestionTextSize
     */
    public function getMainQuestionTextSize()
    {
        return $this->mainQuestionTextSize;
    }

    /**
     * Get answersTextSize
     *
     * @return string $answersTextSize
     */
    public function getAnswersTextSize()
    {
        return $this->answersTextSize;
    }

    /**
     * Set answersTextSize
     *
     * @param string $answersTextSize
     *
     * @return PopUp
     */
    public function setAnswersTextSize($answersTextSize)
    {
        $this->answersTextSize = $answersTextSize;

        return $this;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return PopUp
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return PopUp
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * Get bgColor
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set buttonColor
     *
     * @param string $buttonColor
     *
     * @return PopUp
     */
    public function setButtonColor($buttonColor)
    {
        $this->buttonColor = $buttonColor;

        return $this;
    }

    /**
     * Get buttonColor
     *
     * @return string
     */
    public function getButtonColor()
    {
        return $this->buttonColor;
    }

    /**
     * Set textColor
     *
     * @param string $textColor
     *
     * @return PopUp
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * Get textColor
     *
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return PopUp
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
     * Set note
     *
     * @param string $note
     *
     * @return PopUp
     */
    public function setNote($note)
    {
        $this->note = $note;

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

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
        $this->imageHashName = $image;
    }

    public function getImage()
    {
        return $this->imageHashName;
    }

    /**
     * Set borderStyle
     *
     * @param \AppBundle\Entity\BorderStyle $borderStyle
     * @return BorderStyle
     */
    public function setBorderStyle(\AppBundle\Entity\BorderStyle $borderStyle = null)
    {
        $this->borderStyle = $borderStyle;

        return $this;
    }

    /**
     * Get BorderStyle
     *
     * @return \AppBundle\Entity\BorderStyle
     */
    public function getBorderStyle()
    {
        return $this->borderStyle;
    }

    /**
     * Add site
     *
     * @param \AppBundle\Entity\Site $site
     * @return PopUp
     */
    public function addSite(\AppBundle\Entity\Site $site)
    {
        $this->site[] = $site;

        return $this;
    }
}

