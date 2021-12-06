<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdoradorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Adorador
{
    
    const color0 = "red";
    const color1 = "#44FF44";
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $poblacion;


    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $sustitucionfranja;

    /**
     * @ORM\ManyToMany(targetEntity="DiasemanaHora", inversedBy="adoradores")
     */
    private $diasemanahoras;


    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $baja;

    /**
     * @ORM\Column(type="integer",nullable=true,  options={"default":0})
     */
    private $tipo;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

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

    public function getColor() { return self::color1; }

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

    public static function getDayofweek() {
        $ret = [1 => 'Lunes', 2=>'Martes', 3=> 'Miércoles', 4=> 'Jueves', 5=> 'Viernes', 6=> 'Sábado', 0=> 'Domingo'];
        return $ret;
    }

    public function getBaja(): ?bool
    {
        return $this->baja;
    }

    public function setBaja(?bool $baja): self
    {
        $this->baja = $baja;

        return $this;
    }

    public function getMovil(): ?string
    {
        return $this->movil;
    }

    public function setMovil(?string $movil): self
    {
        $this->movil = $movil;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(?string $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(?int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public static function getTipos() {

        return [0 => 'Activo',1 => 'Solidario', 2 => 'Baja', 3 => 'Web'];
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    { 
        $this->baja = ($this->tipo > 0);
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->onPrePersist();
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }
}
