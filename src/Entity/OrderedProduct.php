<?php

namespace App\Entity;

use App\Repository\OrderedProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderedProductRepository::class)
 */
class OrderedProduct
{
//    /**
//     * @ORM\Id
//     * @ORM\GeneratedValue
//     * @ORM\Column(type="integer")
//     */
//      private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Bill::class, inversedBy="orderedProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bill_id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_id;

//    public function getId(): ?int
//    {
//        return $this->id;
//    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBillId(): ?Bill
    {
        return $this->bill_id;
    }

    public function setBillId(?Bill $bill_id): self
    {
        $this->bill_id = $bill_id;

        return $this;
    }

    public function getProductId(): ?Products
    {
        return $this->product_id;
    }

    public function setProductId(Products $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

}
