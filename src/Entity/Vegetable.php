<?php

namespace App\Entity;

use App\Repository\VegetableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VegetableRepository::class)
 */
class Vegetable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"vegetable"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups ({"vegetable"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"vegetable"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"vegetable"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"vegetable"})
     */
    private $benefits;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"vegetable"})
     */
    private $local;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"vegetable"})
     */
    private $conservation;

    /**
     * @ORM\ManyToMany(targetEntity=Month::class, inversedBy="vegetables")
     * @Groups ({"vegetable"})
     */
    private $months;

    /**
     * @ORM\ManyToOne(targetEntity=Botanical::class, inversedBy="vegetables")
     * @Groups ({"vegetable"})
     */
    private $botanical;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="vegetables")
     * @Groups ({"vegetable"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Ingredient::class, inversedBy="vegetables")
     * @Groups ({"vegetable"})
     */
    private $ingredient;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups ({"vegetable"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups ({"vegetable"})
     */
    private $updated_at;

    public function __construct()
    {
        $this->months = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(string $benefits): self
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function isLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(bool $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getConservation(): ?string
    {
        return $this->conservation;
    }

    public function setConservation(string $conservation): self
    {
        $this->conservation = $conservation;

        return $this;
    }

    /**
     * @return Collection<int, Month>
     */
    public function getMonths(): Collection
    {
        return $this->months;
    }

    public function addMonth(Month $month): self
    {
        if (!$this->months->contains($month)) {
            $this->months[] = $month;
        }

        return $this;
    }

    public function removeMonth(Month $month): self
    {
        $this->months->removeElement($month);

        return $this;
    }

    public function getBotanical(): ?Botanical
    {
        return $this->botanical;
    }

    public function setBotanical(?Botanical $botanical): self
    {
        $this->botanical = $botanical;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}