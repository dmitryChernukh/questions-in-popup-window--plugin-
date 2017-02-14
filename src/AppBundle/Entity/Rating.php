<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RatingRepository")
 */
class Rating
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
     * @ORM\Column(name="site_url", type="string", length=255)
     */
    private $site_url;

    /**
     * @var string
     *
     * @ORM\Column(name="guest_ip", type="string", length=255)
     */
    private $guest_ip;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_one", type="integer", length=2, nullable=true)
     */
    private $rating_one;

    /**
     * @var string
     *
     * @ORM\Column(name="question_text", type="string", length=325, nullable=true)
     */
    private $question_text;

    /**
     * @var integer
     *
     * @ORM\Column(name="pop_up_id", type="integer", length=3, nullable=true)
     */
    private $pop_up_id;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_two", type="integer", length=2, nullable=true)
     */
    private $rating_two;

    /**
     * @var string
     *
     * @ORM\Column(name="response_time", type="string", length=100)
     */
    private $response_time;

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
     * Set site_url
     * @param string $siteUrl
     * @return Rating
     */
    public function setSiteUrl($siteUrl)
    {
        $this->site_url = $siteUrl;

        return $this;
    }

    /**
     * Set question_text
     *
     * @param string $question_text
     * @return Rating
     */
    public function setQuestionText($question_text)
    {
        $this->question_text = $question_text;

        return $this;
    }

    /**
     * Set pop_up_id
     *
     * @param integer $pop_up_id
     * @return Rating
     */
    public function setPopUpId($pop_up_id)
    {
        $this->pop_up_id = $pop_up_id;

        return $this;
    }

    /**
     * Get pop_up_id
     *
     * @return integer
     */
    public function getPopUpId()
    {
        return $this->pop_up_id;
    }

    /**
     * Get question_text
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->question_text;
    }

    /**
     * Get site_url
     *
     * @return string 
     */
    public function getSiteUrl()
    {
        return $this->site_url;
    }

    /**
     * Set guest_ip
     *
     * @param string $guestIp
     * @return Rating
     */
    public function setGuestIp($guestIp)
    {
        $this->guest_ip = $guestIp;

        return $this;
    }

    /**
     * Get guest_ip
     *
     * @return string 
     */
    public function getGuestIp()
    {
        return $this->guest_ip;
    }

    /**
     * Set rating_one
     *
     * @param integer $ratingOne
     * @return Rating
     */
    public function setRatingOne($ratingOne)
    {
        $this->rating_one = $ratingOne;

        return $this;
    }

    /**
     * Get rating_one
     *
     * @return integer 
     */
    public function getRatingOne()
    {
        return $this->rating_one;
    }

    /**
     * Set rating_two
     *
     * @param integer $ratingTwo
     * @return Rating
     */
    public function setRatingTwo($ratingTwo)
    {
        $this->rating_two = $ratingTwo;

        return $this;
    }

    /**
     * Get rating_two
     *
     * @return integer 
     */
    public function getRatingTwo()
    {
        return $this->rating_two;
    }

    /**
     * Set response_time
     *
     * @param string $responseTime
     * @return Rating
     */
    public function setResponseTime($responseTime)
    {
        $this->response_time = $responseTime;

        return $this;
    }

    /**
     * Get response_time
     *
     * @return string 
     */
    public function getResponseTime()
    {
        return $this->response_time;
    }
}
