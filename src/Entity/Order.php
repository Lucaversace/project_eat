<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=StateOrder::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Restorer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @ORM\OneToMany(targetEntity=LineArticle::class, mappedBy="idOrder", orphanRemoval=true)
     */
    private $lineArticle;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function __construct()
    {
        $this->lineArticle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?StateOrder
    {
        return $this->state;
    }

    public function setState(StateOrder $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRestaurant(): ?Restorer
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restorer $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * @return Collection|LineArticle[]
     */
    public function getLineArticle(): Collection
    {
        return $this->lineArticle;
    }

    public function addLineArticle(LineArticle $lineArticle): self
    {
        if (!$this->lineArticle->contains($lineArticle)) {
            $this->lineArticle[] = $lineArticle;
            $lineArticle->setIdOrder($this);
        }

        return $this;
    }

    public function removeLineArticle(LineArticle $lineArticle): self
    {
        if ($this->lineArticle->removeElement($lineArticle)) {
            // set the owning side to null (unless already changed)
            if ($lineArticle->getIdOrder() === $this) {
                $lineArticle->setIdOrder(null);
            }
        }

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
}
