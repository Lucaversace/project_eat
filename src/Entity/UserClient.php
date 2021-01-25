<?php

namespace App\Entity;

use App\Repository\UserClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserClientRepository::class)
 */
class UserClient extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $wallet;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="userClient")
     */
    private $notes;

    public function __construct()
    {
        parent::__construct();
        $this->notes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    public function setWallet(?float $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setUserClient($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUserClient() === $this) {
                $note->setUserClient(null);
            }
        }

        return $this;
    }

}
