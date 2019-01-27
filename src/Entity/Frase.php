<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FraseRepository")
 */
class Frase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $texto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }
 
    public function setAutor(?string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }


    public function getTextoshort(): ?string
    {
        return (strlen($this->texto) > 20)?substr($this->texto,0,50) . "...":$this->texto;
    }

    public function getActiva()
    {
        return $this->activa;
    }

    public function setActiva($activa): self
    {
        $this->activa = $activa;

        return $this;
    }
}
