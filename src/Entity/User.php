<?php
namespace App\Entity;
use App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime", length=100)
     */
    protected $joined;



    public function toArray()
    {
        return get_object_vars($this);
    }


    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }


    public function getPassword()
    {
        return $this->password;
    }


    public function getJoined()
    {
        return $this->joined;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function setJoined($date)
    {
        $this->joined = $date;
    }
}
