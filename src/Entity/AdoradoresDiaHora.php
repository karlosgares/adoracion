<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdoradoresDiaHoraRepository")
 */
class AdoradoresDiaHora
{

    public static $diasSemana = [
        1 => "Lunes",
        2 => "Martes",
        3 => "Miércoles",
        4 => "Jueves",
        5 => "Viernes",
        6 => "Sábado",
        0 => "Domingo",
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dia;

    /**
     * @ORM\Column(type="integer")
     */
    private $hora;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDia(): ?int
    {
        return $this->dia;
    }

    public function setDia(int $dia): self
    {
        $this->dia = $dia;

        return $this;
    }

    public function getHora(): ?int
    {
        return $this->hora;
    }

    public function setHora(int $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }
}
