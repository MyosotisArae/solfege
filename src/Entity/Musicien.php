<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Musicien
 *
 * @ORM\Table(name="musicien")
 * @ORM\Entity(repositoryClass="App\Repository\MusicienRepository")
 * @UniqueEntity(fields={"username"}, message="Il existe déjà un compte avec ce nom.")
 */
class Musicien implements UserInterface, \Serializable
{
    public function __construct()
    {
        $this->isActive = true;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", nullable=false)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->genre,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->genre,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($n)
    {
        $this->username = $n;
        return $this;
    }

    public function setGenre($g)
    {
        $this->genre = $g;
        return $this;
    }

    public function setPassword($pwd)
    {
        $this->password = $pwd;
        return $this;
    }

    public function setIsActive($a)
    {
        $this->isActive = $a;
        return $this;
    }
}
