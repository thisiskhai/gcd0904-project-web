<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Integer;
use App\Entity\Products;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="productId", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $product_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userId", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $user_id;

    /**
     * @ORM\Column(type="integer")
     */

    public $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getProductId(): ?Products
    {
        return $this->product_id;
    }

    public function setProductId(?Products $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }
    public function getTotal(): float
    {
        return $this->getProductId()->getPrice() * $this->getQuantity();
    }
}