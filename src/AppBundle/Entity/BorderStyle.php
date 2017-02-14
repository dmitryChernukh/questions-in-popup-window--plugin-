<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * borderStyle
 *
 * @ORM\Table(name="border_style")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\borderStyleRepository")
 */
class BorderStyle
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PopUp", mappedBy="borderStyle")
     */
    protected $popUp;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ResultsPopUp", mappedBy="borderStyle")
     */
    protected $resultsPopUp;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function __toString() {
        return (string)$this->type;
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

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return borderStyle
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return borderStyle
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

    /**
     * Add popUp
     *
     * @param \AppBundle\Entity\PopUp $popUp
     * @return PopUp
     */
    public function addPopUp(\AppBundle\Entity\PopUp $popUp)
    {
        $this->popUp[] = $popUp;

        return $this;
    }

    /**
     * Add resultPopUp
     *
     * @param \AppBundle\Entity\ResultsPopUp $ResultsPopUp
     * @return PopUp
     */
    public function addResultsPopUp(\AppBundle\Entity\ResultsPopUp $ResultsPopUp)
    {
        $this->resultsPopUp[] = $ResultsPopUp;

        return $this;
    }

    //ResultsPopUp

    /**
     * Remove popUp
     *
     * @param \AppBundle\Entity\PopUp $popUp
     */
    public function removePopUp(\AppBundle\Entity\PopUp $popUp)
    {
        $this->popUp->removeElement($popUp);
    }

    /**
     * Get popUp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPopUp()
    {
        return $this->popUp;
    }

    /**
     * Get popUp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultPopUp()
    {
        return $this->resultsPopUp;
    }
}

