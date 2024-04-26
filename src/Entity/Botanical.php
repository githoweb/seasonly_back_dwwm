<?php

namespace App\Entity;

use App\Repository\BotanicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BotanicalRepository::class)
 */
class Botanical
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Vegetable::class, mappedBy="botanical")
     */
    private $vegetables;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    public function __construct()
    {
        $this->vegetables = new ArrayCollection();
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
     * @return Collection<int, Vegetable>
     */
    public function getVegetables(): Collection
    {
        return $this->vegetables;
    }

    public function addVegetable(Vegetable $vegetable): self
    {
        if (!$this->vegetables->contains($vegetable)) {
            $this->vegetables[] = $vegetable;
            $vegetable->setBotanical($this);
        }

        return $this;
    }

    public function removeVegetable(Vegetable $vegetable): self
    {
        if ($this->vegetables->removeElement($vegetable)) {
            // set the owning side to null (unless already changed)
            if ($vegetable->getBotanical() === $this) {
                $vegetable->setBotanical(null);
            }
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
}