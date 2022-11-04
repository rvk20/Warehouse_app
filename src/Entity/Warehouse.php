<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'warehouse', targetEntity: ProductProperties::class)]
    private Collection $productProperties;

    #[ORM\OneToMany(mappedBy: 'warehouse', targetEntity: ProductState::class)]
    private Collection $productStates;

    public function __construct()
    {
        $this->productProperties = new ArrayCollection();
        $this->productStates = new ArrayCollection();
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

    /**
     * @return Collection<int, ProductProperties>
     */
    public function getProductProperties(): Collection
    {
        return $this->productProperties;
    }

    public function addProductProperty(ProductProperties $productProperty): self
    {
        if (!$this->productProperties->contains($productProperty)) {
            $this->productProperties->add($productProperty);
            $productProperty->setWarehouse($this);
        }

        return $this;
    }

    public function removeProductProperty(ProductProperties $productProperty): self
    {
        if ($this->productProperties->removeElement($productProperty)) {
            // set the owning side to null (unless already changed)
            if ($productProperty->getWarehouse() === $this) {
                $productProperty->setWarehouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductState>
     */
    public function getProductStates(): Collection
    {
        return $this->productStates;
    }

    public function addProductState(ProductState $productState): self
    {
        if (!$this->productStates->contains($productState)) {
            $this->productStates->add($productState);
            $productState->setWarehouse($this);
        }

        return $this;
    }

    public function removeProductState(ProductState $productState): self
    {
        if ($this->productStates->removeElement($productState)) {
            // set the owning side to null (unless already changed)
            if ($productState->getWarehouse() === $this) {
                $productState->setWarehouse(null);
            }
        }

        return $this;
    }
}
