<?php

namespace App\Entity;

use App\Repository\RestorerRepository;
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
}
