<?php

namespace App\Entity;

use App\Repository\RestorerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestorerRepository::class)
 */
class Restorer extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restaurantName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Dish::class, mappedBy="restaurant", cascade={"persist", "remove"})
     */
    private $dishs;


    public function __construct()
    {
        $this->dishs = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getRestaurantName(): ?string
    {
        return $this->restaurantName;
    }

    public function setRestaurantName(string $restaurantName): self
    {
        $this->restaurantName = $restaurantName;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDishs(): Collection
    {
        return $this->dishs;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dishs->contains($dish)) {
            $this->dishs[] = $dish;
            $dish->setRestaurant($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dishs->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getRestaurant() === $this) {
                $dish->setRestaurant(null);
            }
        }

        return $this;
    }

}
