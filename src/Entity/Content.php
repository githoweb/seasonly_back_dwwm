<?php

namespace App\Entity;

use ORM\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"recipe"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups ({"recipe"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Ingredient::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"recipe"})
     */
    private $ingredient;

    /**
     * @ORM\ManyToOne(targetEntity=Measure::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"recipe"})
     */
    private $measure;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="contents")
     * @Groups ({"content"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $recipe;

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

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getMeasure(): ?Measure
    {
        return $this->measure;
    }

    public function setMeasure(?Measure $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}