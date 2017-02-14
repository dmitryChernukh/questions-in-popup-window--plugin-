<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * results_pop_up
 *
 * @ORM\Table(name="results_pop_up")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\results_pop_upRepository")
 * @Vich\Uploadable
 */
class ResultsPopUp
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", length=5, nullable=true)
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="popUpId", type="integer", length=5, nullable=true)
     */
    private $popUpId;

    /**
     * @var int
     *
     * @ORM\Column(name="buttonTextSize", type="integer", length=5, nullable=true)
     */
    private $buttonTextSize;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonShadow", type="string", length=255, nullable=true)
     */
    private $buttonShadow;

    /**
     * @var int
     *
     * @ORM\Column(name="indTextWidthOne", type="integer", length=5, nullable=true)
     */
    private $indTextWidthOne = 95;

    /**
     * @var int
     *
     * @ORM\Column(name="indTextWidthTwo", type="integer", length=5, nullable=true)
     */
    private $indTextWidthTwo = 95;

    /**
     * @var int
     *
     * @ORM\Column(name="indTextWidthThree", type="integer", length=5, nullable=true)
     */
    private $indTextWidthThree = 95;


    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", length=5, nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, nullable=true)
     * @var string
     */
    private $imageFileHead;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $statusCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, nullable=true)
     * @var string
     */
    private $imageFileBody;

    /**
     * @Vich\UploadableField(name="head_image", mapping="product_images", fileNameProperty="imageFileHead")
     * @var File
     */
    private $head_image;


    /**
     * Constructor
     */
    public function __construct()
    {
//        parent::__construct();
        $this->borderStyle = new ArrayCollection();

    }

    /**
     * @Vich\UploadableField(name="body_image", mapping="product_images", fileNameProperty="imageFileBody")
     * @var File
     */
    private $body_image;

    /**
     * @var string
     *
     * @ORM\Column(name="bgColor", type="string", length=255, nullable=true)
     */
    private $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="imageSizeHead", type="integer", length=5, nullable=true)
     */
    private $imageSizeHead;

    /**
     * @var integer
     *
     * @ORM\Column(name="buttonTopMargin", type="integer", length=5, nullable=true)
     */
    private $buttonTopMargin;

    /**
     * @var integer
     *
     * @ORM\Column(name="imageTopMargin", type="integer", length=5, nullable=true)
     */
    private $imageTopMargin;

    //image margin bottom

    /**
     * @var integer
     *
     * @ORM\Column(name="imageMarginBottom", type="integer", length=5, nullable=true)
     */
    private $imageMarginBottom;

    /**
     * @var integer
     *
     * @ORM\Column(name="textBlockMessageSize", type="integer", length=7, nullable=true)
     */
    private $textBlockMessageSize;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonTextColour", type="string", length=255, nullable=true)
     */
    private $buttonTextColour;

    //imageLink
    /**
     * @var string
     *
     * @ORM\Column(name="imageLink", type="string", length=255, nullable=true)
     */
    private $imageLink;

    /**
     * @var string
     *
     * @ORM\Column(name="textBlockMessage", type="string", length=450, nullable=true)
     */
    private $textBlockMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="separateStatus", type="string", length=450, nullable=true)
     */
    private $separateStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="blockStatus", type="string", length=24, nullable=true)
     */
    private $blockStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="imageSizeBody", type="integer", length=5, nullable=true)
     */
    private $imageSizeBody;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonColor", type="string", length=255, nullable=true)
     */
    private $buttonColor;

    /**
     * @var string
     *
     * @ORM\Column(name="textColor", type="string", length=255, nullable=true)
     */
    private $textColor;

    /**
     * @var int
     *
     * @ORM\Column(name="updated", type="integer", length=10, nullable=true)
     */
    private $updated;

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
     * @var int
     *
     * @ORM\Column(name="borderWidth", type="integer", length=5, nullable=true)
     */
    private $borderWidth;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BorderStyle", inversedBy="popUp")
     *
     * @var AppBundle\Entity\BorderStyle;
     */
    private $borderStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="mainTitle", type="string", length=255, nullable=true)
     */
    private $mainTitle;

    /**
     * @var int
     *
     * @ORM\Column(name="mainTitleTextSize", type="integer", length=5, nullable=true)
     */
    private $mainTitleTextSize;

    /**
     * @var string
     *
     * @ORM\Column(name="ratingTextOne", type="string", length=255, nullable=true)
     */
    private $ratingTextOne;

    /**
     * @var string
     *
     * @ORM\Column(name="ratingTextTwo", type="string", length=255, nullable=true)
     */
    private $ratingTextTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="ratingTextThree", type="string", length=255, nullable=true)
     */
    private $ratingTextThree;

    /**
     * @var int
     *
     * @ORM\Column(name="countRating", type="integer", length=5, nullable=true)
     */
    private $countRating = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="ratingTextSize", type="integer", length=5, nullable=true)
     */
    private $ratingTextSize;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonText", type="string", length=255, nullable=true)
     */
    private $buttonText;

    /**
     * @var int
     *
     * @ORM\Column(name="buttonWidth", type="integer", length=5, nullable=true)
     */
    private $buttonWidth;

    /**
     * @var int
     *
     * @ORM\Column(name="textContainerFluidMargin", type="integer", length=5, nullable=true)
     */
    private $textContainerFluidMargin;

    /**
     * @var int
     *
     * @ORM\Column(name="mainPopupPadding", type="integer", length=5, nullable=true)
     */
    private $mainPopupPadding;

    /**
     * @var int
     *
     * @ORM\Column(name="buttonHeight", type="integer", length=5, nullable=true)
     */
    private $buttonHeight;

    /**
     * @var int
     *
     * @ORM\Column(name="ratingStarSize", type="integer", length=5, nullable=true)
     */
    private $ratingStarSize;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;


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
     * @return results_pop_up
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
     * Set height
     *
     * @param integer $height
     *
     * @return results_pop_up
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
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

    //statusCode
    /**
     * Set height
     *
     * @param string $statusCode
     *
     * @return results_pop_up
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }


    /**
     * Set width
     *
     * @param integer $width
     *
     * @return results_pop_up
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set popUpId
     *
     * @param integer $popUpId
     *
     * @return results_pop_up
     */
    public function setPopUpId($popUpId)
    {
        $this->popUpId = $popUpId;

        return $this;
    }

    /**
     * Get popUpId
     *
     * @return int
     */
    public function getPopUpId()
    {
        return $this->popUpId;
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
     * Set headImage
     *
     * @param string $headImage
     *
     * @return results_pop_up
     */
    public function setHeadImage($headImage)
    {
        $this->head_image = $headImage;

        return $this;
    }

    /**
     * Get headImage
     *
     * @return string
     */
    public function getHeadImage()
    {
        return $this->head_image;
    }

    //body_image
    /**
     * Set bodyImage
     *
     * @param string $bodyImage
     *
     * @return results_pop_up
     */
    public function setBodyImage($bodyImage)
    {
        $this->body_image = $bodyImage;

        return $this;
    }

    /**
     * Get bodyImage
     *
     * @return string
     */
    public function getBodyImage()
    {
        return $this->body_image;
    }

    //buttonShadow
    /**
     * Set buttonShadow
     *
     * @param string $buttonShadow
     *
     * @return results_pop_up
     */
    public function setButtonShadow($buttonShadow)
    {
        $this->buttonShadow = $buttonShadow;

        return $this;
    }

    /**
     * Get buttonShadow
     *
     * @return string
     */
    public function getButtonShadow()
    {
        return $this->buttonShadow;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return results_pop_up
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
     * Set indTextWidthOne
     *
     * @param integer $indTextWidthOne
     *
     * @return results_pop_up
     */
    public function setIndTextWidthOne($indTextWidthOne)
    {
        $this->indTextWidthOne = $indTextWidthOne;

        return $this;
    }

    /**
     * Get indTextWidthOne
     *
     * @return integer
     */
    public function getIndTextWidthOne()
    {
        return $this->indTextWidthOne;
    }

    //$indTextWidthThree;
    /**
     * Set indTextWidthThree
     *
     * @param integer $indTextWidthThree
     *
     * @return results_pop_up
     */
    public function setIndTextWidthThree($indTextWidthThree)
    {
        $this->indTextWidthThree = $indTextWidthThree;

        return $this;
    }

    /**
     * Set textContainerFluidMargin
     *
     * @param integer $textContainerFluidMargin
     *
     * @return results_pop_up
     */
    public function setTextContainerFluidMargin($textContainerFluidMargin)
    {
        $this->textContainerFluidMargin = $textContainerFluidMargin;

        return $this;
    }

    /**
     * Set mainPopupPadding
     *
     * @param integer $mainPopupPadding
     *
     * @return results_pop_up
     */
    public function setMainPopupPadding($mainPopupPadding)
    {
        $this->mainPopupPadding = $mainPopupPadding;

        return $this;
    }

    /**
     * Get mainPopupPadding
     *
     * @return integer
     */
    public function getMainPopupPadding()
    {
        return $this->mainPopupPadding;
    }

    /**
     * Get textContainerFluidMargin
     *
     * @return integer
     */
    public function getTextContainerFluidMargin()
    {
        return $this->textContainerFluidMargin;
    }

    /**
     * Get indTextWidthThree
     *
     * @return integer
     */
    public function getIndTextWidthThree()
    {
        return $this->indTextWidthThree;
    }


    //separateStatus
    /**
     * Set separateStatus
     *
     * @param string $separateStatus
     *
     * @return results_pop_up
     */
    public function setSeparateStatus($separateStatus)
    {
        $this->separateStatus = $separateStatus;

        return $this;
    }

    /**
     * Get separateStatus
     *
     * @return string
     */
    public function getSeparateStatus()
    {
        return $this->separateStatus;
    }

    /**
     * Set buttonColor
     *
     * @param string $buttonColor
     *
     * @return results_pop_up
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
     * @return results_pop_up
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
     * Set indTextWidthTwo
     *
     * @param integer $indTextWidthTwo
     *
     * @return results_pop_up
     */
    public function setIndTextWidthTwo($indTextWidthTwo)
    {
        $this->indTextWidthTwo = $indTextWidthTwo;

        return $this;
    }

    /**
     * Get indTextWidthTwo
     *
     * @return integer
     */
    public function getIndTextWidthTwo()
    {
        return $this->indTextWidthTwo;
    }

    /**
     * Get imageSizeHead
     *
     * @return string
     */
    public function getImageSizeHead(){
        return $this->imageSizeHead;
    }

    //imageTopMargin
    /**
     * Get imageTopMargin
     *
     * @return string
     */
    public function getImageTopMargin(){
        return $this->imageTopMargin;
    }

    /**
     * Set imageTopMargin
     *
     * @param string $imageTopMargin
     *
     * @return results_pop_up
     */
    public function setImageTopMargin($imageTopMargin)
    {
        $this->imageTopMargin = $imageTopMargin;

        return $this;
    }

    /**
     * Set imageSizeHead
     *
     * @param string $imageSizeHead
     *
     * @return results_pop_up
     */
    public function setImageSizeHead($imageSizeHead)
    {
        $this->imageSizeHead = $imageSizeHead;

        return $this;
    }

    /**
     * Get imageSizeBody
     *
     * @return string
     */
    public function getImageSizeBody(){
        return $this->imageSizeBody;
    }

    /**
     * Set imageSizeBody
     *
     * @param string $imageSizeBody
     *
     * @return results_pop_up
     */
    public function setImageSizeBody($imageSizeBody)
    {
        $this->imageSizeBody = $imageSizeBody;

        return $this;
    }

    /**
     * Set updated
     *
     * @param integer $updated
     *
     * @return results_pop_up
     */
    public function setUpdated($updated)
    {
        $this->updated = time();

        return $this;
    }

    /**
     * Get updated
     *
     * @return int
     */
    public function getUpdated()
    {
        if(empty($this->updated) OR $this->updated == NULL){
            $this->updated = 0;
        }
        return $this->updated;
    }

    /**
     * Set borderRadius
     *
     * @param integer $borderRadius
     *
     * @return results_pop_up
     */
    public function setBorderRadius($borderRadius)
    {
        $this->borderRadius = $borderRadius;

        return $this;
    }

    /**
     * Get borderRadius
     *
     * @return int
     */
    public function getBorderRadius()
    {
        return $this->borderRadius;
    }

    /**
     * Set borderColour
     *
     * @param string $borderColour
     *
     * @return results_pop_up
     */
    public function setBorderColour($borderColour)
    {
        $this->borderColour = $borderColour;

        return $this;
    }

    /**
     * Get borderColour
     *
     * @return string
     */
    public function getBorderColour()
    {
        return $this->borderColour;
    }

    /**
     * Set borderWidth
     *
     * @param integer $borderWidth
     *
     * @return results_pop_up
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    //imageLink
    /**
     * Set imageLink
     *
     * @param integer $imageLink
     *
     * @return results_pop_up
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    /**
     * Get imageLink
     *
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * Get borderWidth
     *
     * @return int
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }


    /**
     * Set buttonTopMargin
     *
     * @param integer $buttonTopMargin
     *
     * @return results_pop_up
     */
    public function setButtonTopMargin($buttonTopMargin)
    {
        $this->buttonTopMargin = $buttonTopMargin;

        return $this;
    }

    /**
     * Get buttonTopMargin
     *
     * @return int
     */
    public function getButtonTopMargin()
    {
        return $this->buttonTopMargin;
    }


    /**
     * Set textBlockMessageSize
     *
     * @param integer $textBlockMessageSize
     *
     * @return results_pop_up
     */
    public function setTextBlockMessageSize($textBlockMessageSize)
    {
        $this->textBlockMessageSize = $textBlockMessageSize;

        return $this;
    }

    /**
     * Get textBlockMessageSize
     *
     * @return int
     */
    public function getTextBlockMessageSize()
    {
        return $this->textBlockMessageSize;
    }

    /**
     * Get buttonTextColour
     *
     * @return int
     */
    public function getButtonTextColour()
    {
        return $this->buttonTextColour;
    }

    /**
     * Set buttonTextColour
     *
     * @param string $buttonTextColour
     *
     * @return results_pop_up
     */
    public function setButtonTextColour($buttonTextColour)
    {
        $this->buttonTextColour = $buttonTextColour;
        return $this;
    }

    /**
     * Get textBlockMessage
     *
     * @return string
     */
    public function getTextBlockMessage()
    {
        return $this->textBlockMessage;
    }

    /**
     * Set textBlockMessage
     *
     * @param string $textBlockMessage
     *
     * @return results_pop_up
     */
    public function setTextBlockMessage($textBlockMessage)
    {
        $this->textBlockMessage = $textBlockMessage;

        return $this;
    }

    /**
     * Get blockStatus
     *
     * @return string
     */
    public function getBlockStatus()
    {
        return $this->blockStatus;
    }

    //buttonTextSize
    /**
     * Set buttonTextSize
     *
     * @param integer $buttonTextSize
     *
     * @return results_pop_up
     */
    public function setButtonTextSize($buttonTextSize)
    {
        $this->buttonTextSize = $buttonTextSize;

        return $this;
    }

    /**
     * Get buttonTextSize
     *
     * @return integer
     */
    public function getButtonTextSize()
    {
        return $this->buttonTextSize;
    }

    //imageMarginBottom
    /**
     * Set imageMarginBottom
     *
     * @param string $imageMarginBottom
     *
     * @return results_pop_up
     */
    public function setImageMarginBottom($imageMarginBottom)
    {
        $this->imageMarginBottom = $imageMarginBottom;

        return $this;
    }

    /**
     * Get imageMarginBottom
     *
     * @return integer
     */
    public function getImageMarginBottom()
    {
        return $this->imageMarginBottom;
    }

    /**
     * Set blockStatus
     *
     * @param string $blockStatus
     *
     * @return results_pop_up
     */
    public function setBlockStatus($blockStatus)
    {
        $this->blockStatus = $blockStatus;

        return $this;
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
     * Set mainTitle
     *
     * @param string $mainTitle
     *
     * @return results_pop_up
     */
    public function setMainTitle($mainTitle)
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    /**
     * Get countRating
     *
     * @return integer
     */
    public function getCountRating()
    {
        return $this->countRating;
    }

    /**
     * Set countRating
     *
     * @param integer $countRating
     *
     * @return results_pop_up
     */
    public function setCountRating($countRating)
    {
        $this->countRating = $countRating;

        return $this;
    }

    /**
     * Set ratingTextThree
     *
     * @param string $ratingTextThree
     *
     * @return results_pop_up
     */
    public function setRatingTextThree($ratingTextThree)
    {
        $this->ratingTextThree = $ratingTextThree;

        return $this;
    }

    /**
     * Get ratingTextThree
     *
     * @return string
     */
    public function getRatingTextThree()
    {
        return $this->ratingTextThree;
    }


    /**
     * Get mainTitle
     *
     * @return string
     */
    public function getMainTitle()
    {
        return $this->mainTitle;
    }

    /**
     * Set mainTitleTextSize
     *
     * @param integer $mainTitleTextSize
     *
     * @return results_pop_up
     */
    public function setMainTitleTextSize($mainTitleTextSize)
    {
        $this->mainTitleTextSize = $mainTitleTextSize;

        return $this;
    }

    /**
     * Get mainTitleTextSize
     *
     * @return int
     */
    public function getMainTitleTextSize()
    {
        return $this->mainTitleTextSize;
    }

    /**
     * Set ratingTextOne
     *
     * @param string $ratingTextOne
     *
     * @return results_pop_up
     */
    public function setRatingTextOne($ratingTextOne)
    {
        $this->ratingTextOne = $ratingTextOne;

        return $this;
    }

    /**
     * Get ratingTextOne
     *
     * @return string
     */
    public function getRatingTextOne()
    {
        return $this->ratingTextOne;
    }

    /**
     * Set ratingTextTwo
     *
     * @param string $ratingTextTwo
     *
     * @return results_pop_up
     */
    public function setRatingTextTwo($ratingTextTwo)
    {
        $this->ratingTextTwo = $ratingTextTwo;

        return $this;
    }

    /**
     * Get ratingTextTwo
     *
     * @return string
     */
    public function getRatingTextTwo()
    {
        return $this->ratingTextTwo;
    }

    /**
     * Set ratingTextSize
     *
     * @param integer $ratingTextSize
     *
     * @return results_pop_up
     */
    public function setRatingTextSize($ratingTextSize)
    {
        $this->ratingTextSize = $ratingTextSize;

        return $this;
    }

    /**
     * Get ratingTextSize
     *
     * @return int
     */
    public function getRatingTextSize()
    {
        return $this->ratingTextSize;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return results_pop_up
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
     * Set buttonText
     *
     * @param string $buttonText
     *
     * @return results_pop_up
     */
    public function setButtonText($buttonText)
    {
        $this->buttonText = $buttonText;

        return $this;
    }

    /**
     * Get buttonText
     *
     * @return string
     */
    public function getButtonText()
    {
        return $this->buttonText;
    }

    /**
     * Set buttonWidth
     *
     * @param integer $buttonWidth
     *
     * @return results_pop_up
     */
    public function setButtonWidth($buttonWidth)
    {
        $this->buttonWidth = $buttonWidth;

        return $this;
    }

    /**
     * Get buttonWidth
     *
     * @return int
     */
    public function getButtonWidth()
    {
        return $this->buttonWidth;
    }

    /**
     * Set buttonHeight
     *
     * @param integer $buttonHeight
     *
     * @return results_pop_up
     */
    public function setButtonHeight($buttonHeight)
    {
        $this->buttonHeight = $buttonHeight;

        return $this;
    }

    /**
     * Get buttonHeight
     *
     * @return int
     */
    public function getButtonHeight()
    {
        return $this->buttonHeight;
    }

    /**
     * Set ratingStarSize
     *
     * @param integer $ratingStarSize
     *
     * @return results_pop_up
     */
    public function setRatingStarSize($ratingStarSize)
    {
        $this->ratingStarSize = $ratingStarSize;

        return $this;
    }

    /**
     * Get ratingStarSize
     *
     * @return int
     */
    public function getRatingStarSize()
    {
        return $this->ratingStarSize;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return results_pop_up
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

    public function getImageFileHead(){
        return $this->imageFileHead;
    }

    /**
     * Set note
     *
     * @param string $imageFileHead
     *
     * @return results_pop_up
     */
    public function setImageFileHead($imageFileHead){

        $this->imageFileHead = $imageFileHead;

        return $this;
    }

    public function getImageFileBody(){
        return $this->imageFileBody;
    }

    /**
     * Set note
     *
     * @param string $imageFileBody
     *
     * @return results_pop_up
     */
    public function setImageFileBody($imageFileBody){

        $this->imageFileBody = $imageFileBody;

        return $this;
    }
}

