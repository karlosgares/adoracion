<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdoradorRepository")
 */
class Adorador
{
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
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $sustitucionfranja;

    /**
     * @ORM\ManyToMany(targetEntity="DiasemanaHora", inversedBy="adoradores")
     */
    private $diasemanahoras;

    public function __construct()
    {
        $this->diasemanahoras = new ArrayCollection();
    }

    public function __toString() {
        return $this->getNombre() . " " . $this->getApellidos() . " - " . $this->getTelefono(); 
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

    public function getColor() { return 'gold'; }

    public function getSustitucionfranja(): ?int
    {
        return $this->sustitucionfranja;
    }

    public function setSustitucionfranja(?int $sustitucionfranja): self
    {
        $this->sustitucionfranja = $sustitucionfranja;

        return $this;
    }

    public static function getSustitucionfranjas() {

        return ['0:00-6:00 Madrugada','6:00-12:00 Mañana','12:00-18:00 Tarde', '18:00-24:00 Noche'];
    }
}
