<?php

namespace App\Entity;

use ORM\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"recipe", "member"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"member", "recipe"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"recipe"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"recipe"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"recipe"})
     */
    private $instruction;

    /**
     * @ORM\Column(type="smallint")
     * @Groups ({"recipe"})
     */
    private $duration;

    /**
     * @ORM\Column(type="smallint")
     * @Groups ({"recipe"})
     */
    private $serving;

    /**
     * @ORM\ManyToOne(targetEntity=Meal::class, inversedBy="recipes")
     * @Groups ({"recipe"})
     */
    private $meal;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, mappedBy="recipes")
     */
    private $members;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups ({"recipe"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups ({"recipe"})
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="recipe")
     * @Groups ({"recipe"})
     */
    private $contents;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->contents = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getServing(): ?int
    {
        return $this->serving;
    }

    public function setServing(int $serving): self
    {
        $this->serving = $serving;

        return $this;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->addRecipe($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            $member->removeRecipe($this);
        }

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

    /**
     * @return Collection<int, Content>
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setRecipe($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getRecipe() === $this) {
                $content->setRecipe(null);
            }
        }

        return $this;
    }
}