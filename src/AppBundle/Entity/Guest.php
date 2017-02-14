<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guest
 *
 * @ORM\Table(name="guest")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GuestRepository")
 */
class Guest
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
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255)
     */
    private $ip_address;

    /**
     * @var string
     *
     * @ORM\Column(name="browser_name", type="string", length=255, nullable=true)
     */
    private $browser_name;

    /**
     * @var string
     *
     * @ORM\Column(name="browser_code_name", type="string", length=100, nullable=true)
     */
    private $browser_code_name;

    /**
     * @var int
     *
     * @ORM\Column(name="last_connect", length=10, type="string", length=100, nullable=true)
     * @ORM\Column(type="datetime")
     */
    private $last_connect;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=100, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=100, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="accuracy", type="string", length=100, nullable=true)
     */
    private $accuracy;

    /**
     * @var string
     *
     * @ORM\Column(name="local_identifire", type="string", length=255, nullable=true)
     */
    private $local_identifire;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;


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
     * Set name
     *
     * @param string $name
     * @return Guest
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
     * Set ip_address
     *
     * @param string $ipAddress
     * @return Guest
     */
    public function setIpAddress($ipAddress)
    {
        $this->ip_address = $ipAddress;

        return $this;
    }

    /**
     * Get ip_address
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set browser_name
     *
     * @param string $browserName
     * @return Guest
     */
    public function setBrowserName($browserName)
    {
        $this->browser_name = $browserName;

        return $this;
    }

    /**
     * Get browser_name
     *
     * @return string 
     */
    public function getBrowserName()
    {
        return $this->browser_name;
    }

    /**
     * Set browser_code_name
     *
     * @param string $browserCodeName
     * @return Guest
     */
    public function setBrowserCodeName($browserCodeName)
    {
        $this->browser_code_name = $browserCodeName;

        return $this;
    }

    /**
     * Get browser_code_name
     *
     * @return string 
     */
    public function getBrowserCodeName()
    {
        return $this->browser_code_name;
    }

    /**
     * Set last_connect
     *
     * @param string $lastConnect
     * @return Guest
     */
    public function setLastConnect($lastConnect)
    {
        $this->last_connect = $lastConnect;

        return $this;
    }

    /**
     * Get last_connect
     *
     * @return string
     */
    public function getLastConnect()
    {
        return $this->last_connect;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Guest
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Guest
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Guest
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set accuracy
     *
     * @param string $accuracy
     * @return Guest
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    /**
     * Get accuracy
     *
     * @return string 
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * Set local_identifire
     *
     * @param string $localIdentifire
     * @return Guest
     */
    public function setLocalIdentifire($localIdentifire)
    {
        $this->local_identifire = $localIdentifire;

        return $this;
    }

    /**
     * Get local_identifire
     *
     * @return string 
     */
    public function getLocalIdentifire()
    {
        return $this->local_identifire;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Guest
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
