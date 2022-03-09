<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
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
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @ORM\Column(type="float")
     */
    public $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $category_id;
    
    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="product_id", cascade={"persist", "remove"})
     */
    public $productId;

    public function __construct()
    {
        $this->productId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
    /**
     * @return Collection<int, Cartr>
     */

    public function getCart(): Collection
    {
        return $this->productId;
    }

    public function addCart(Cart $productid): self
    {
        if (!$this->productId->contains($productid)) {
            $this->productId[] = $productid;
            $productid->setProductId($this);
        }

        return $this;
    }

    public function removeCart(Cart $productid): self
    {
        if ($this->productId->removeElement($productid)) {
            // set the owning side to null (unless already changed)
            if ($productid->getProductId() === $this) {
                $productid->setProductId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

}