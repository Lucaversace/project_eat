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

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->lineArticle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

}
