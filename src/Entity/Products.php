<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 * @UniqueEntity(fields={"code"})
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $added_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $discontinued_at;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true, options={"unsigned"=true})
     */
    private $price;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true, "default"=0})
     */
    private $stock;

    /**
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $updated_at;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->added_at;
    }

    public function setAddedAt(?\DateTimeInterface $added_at): self
    {
        $this->added_at = $added_at;

        return $this;
    }

    public function getDiscontinuedAt(): ?\DateTimeImmutable
    {
        return $this->discontinued_at;
    }

    public function setDiscontinuedAt(?\DateTimeImmutable $discontinued_at): self
    {
        $this->discontinued_at = $discontinued_at;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
