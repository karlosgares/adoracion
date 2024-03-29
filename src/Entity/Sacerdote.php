<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SacerdoteRepository")
 */
class Sacerdote
{   
    const color = "#AACA31";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\ManyToMany(targetEntity="DiasemanaHora", inversedBy="sacerdotes")
     */
    private $diasemanahoras;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __toString() {
        return $this->getNombre() . " " . $this->getApellidos(); 
    }


    public function __construct()
    {
        $this->diasemanahoras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return Collection|DiasemanaHora[]
     */
    public function getDiasemanahoras(): Collection
    {
        return $this->diasemanahoras;
    }

    public function addDiasemanahora(DiasemanaHora $diasemanahora): self
    {
        if (!$this->diasemanahoras->contains($diasemanahora)) {
            $this->diasemanahoras[] = $diasemanahora;
        }

        return $this;
    }

    public function removeDiasemanahora(DiasemanaHora $diasemanahora): self
    {
        if ($this->diasemanahoras->contains($diasemanahora)) {
            $this->diasemanahoras->removeElement($diasemanahora);
        }

        return $this;
    }

    public function getColor() { return self::color; }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
}
