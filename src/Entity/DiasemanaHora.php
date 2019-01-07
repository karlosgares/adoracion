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
     * @ORM\Column(type="integer")
     */
    private $tipo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hora;


    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hhmm;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idcopia;

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

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getHhmm(): ?\DateTimeInterface
    {
        return $this->hhmm;
    }

    public function setHhmm(\DateTimeInterface $hhmm): self
    {
        $this->hhmm = $hhmm;

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

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
    public function getColor() { return 'silver'; }
}
