<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 *
 * @ORM\Table(name="answers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnswersRepository")
 */
class Answers
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="answer")
     *
     * @var AppBundle\Entity\Question;
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=255)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="identifire", type="text", nullable=true)
     */
    private $identifire;

    /**
     * @var string
     *
     * @ORM\Column(name="identifireStepID", type="string", length=255, nullable=true)
     */
    private $identifireStepID;

    public function __toString() {
        return $this->answer;
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
     * Set answer
     *
     * @param string $answer
     *
     * @return Answers
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Set identifire
     *
     * @param string $identifire
     *
     * @return Answers
     */
    public function setIdentifire($identifire)
    {
        $this->identifire = $identifire;

        return $this;
    }

    /**
     * Get identifire
     *
     * @return string
     */
    public function getIdentifire()
    {
        if($this->identifire == null){
            $this->identifire = 0;
        }
        return $this->identifire;
    }

    /**
     * Set identifireStepID
     *
     * @param integer $identifireStepID
     *
     * @return Answers
     */
    public function setIdentifireStepID($identifireStepID)
    {
        $this->identifireStepID = $identifireStepID;

        return $this;
    }

    /**
     * Get identifireStepID
     *
     * @return integer
     */
    public function getIdentifireStepID()
    {
        if($this->identifireStepID == null){
            $this->identifireStepID = 0;
        }
        return $this->identifireStepID;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Question $question
     * @return Customer
     */
    public function setQuestion(\AppBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Answers
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
}

