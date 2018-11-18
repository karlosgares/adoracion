<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiasemanaHoraRepository")
 */
class DiasemanaHora
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $diasemana;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayofweek;


    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Sacerdote", mappedBy="diasemanahoras")
     */
    private $sacerdotes;


    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Adorador", mappedBy="diasemanahoras")
     */
    private $adoradores;

    /**
     * @ORM\Column(type="time")
     */
    private $hora;

    public function __construct()
    {
        $this->sacerdotes = new ArrayCollection();
        $this->adoradores = new ArrayCollection();
    }

    public function __toString(){
        return sprintf("%s. %s:00 a %s:00", $this->getDiasemana(), $this->getHora()->format("H"), $this->getHora()->format("H")+1);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiasemana(): ?string
    {
        return $this->diasemana;
    }

    public function setDiasemana(string $diasemana): self
    {
        $this->diasemana = $diasemana;

        return $this;
    }

    public function getDayofweek(): ?int
    {
        return $this->dayofweek;
    }

    public function setDayofweek(int $dayofweek): self
    {
        $this->dayofweek = $dayofweek;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * @return Collection|Sacerdote[]
     */
    public function getSacerdotes(): Collection
    {
        return $this->sacerdotes;
    }

    public function addSacerdote(Sacerdote $sacerdote): self
    {
        if (!$this->sacerdotes->contains($sacerdote)) {
            $this->sacerdotes[] = $sacerdote;
            $sacerdote->addDiasemanahora($this);
        }

        return $this;
    }

    public function removeSacerdote(Sacerdote $sacerdote): self
    {
        if ($this->sacerdotes->contains($sacerdote)) {
            $this->sacerdotes->removeElement($sacerdote);
            $sacerdote->removeDiasemanahora($this);
        }

        return $this;
    }

    /**
     * @return Collection|Adorador[]
     */
    public function getAdoradores(): Collection
    {
        return $this->adoradores;
    }

    public function addAdoradore(Adorador $adoradore): self
    {
        if (!$this->adoradores->contains($adoradore)) {
            $this->adoradores[] = $adoradore;
            $adoradore->addDiasemanahora($this);
        }

        return $this;
    }

    public function removeAdoradore(Adorador $adoradore): self
    {
        if ($this->adoradores->contains($adoradore)) {
            $this->adoradores->removeElement($adoradore);
            $adoradore->removeDiasemanahora($this);
        }

        return $this;
    }
}
