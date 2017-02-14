<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Answers", mappedBy="question")
     */
    protected $answers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Site", mappedBy="question")
     */
    protected $site;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * Add answer
     *
     * @param \AppBundle\Entity\Answers $answers
     * @return User
     */
    public function addCustomer(\AppBundle\Entity\Answers $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \AppBundle\Entity\Answers $answers
     */
    public function removeCustomer(\AppBundle\Entity\Answers $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function __toString() {
        return $this->question;
    }

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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="popUpId", type="integer", nullable=true)
     */
    private $popUpID;

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
     * Set question
     *
     * @param string $text
     *
     * @return Question
     */
    public function setQuestion($text)
    {
        $this->question = $text;

        return $this;
    }

    /**
     * Set popUpID
     *
     * @param string $popUpID
     *
     * @return Question
     */
    public function setPopUpID($popUpID)
    {
        $this->popUpID = $popUpID;

        return $this;
    }

    /**
     * Get popUpID
     *
     * @return int
     */
    public function getPopUpID()
    {
        return $this->popUpID;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
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

