<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $age;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    public $userId;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
    }
 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

     /**
     * @return Collection<int, Cart>
     */

    public function getUser(): Collection
    {
        return $this->userId;
    }

    public function addUser(Cart $userid): self
    {
        if (!$this->userId->contains($userid)) {
            $this->userId[] = $userid;
            $userid->setUserId($this);
        }

        return $this;
    }

    public function removeUser(Cart $userid): self
    {
        if ($this->userId->removeElement($userid)) {
            // set the owning side to null (unless already changed)
            if ($userid->getUserId() === $this) {
                $userid->setUserId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}