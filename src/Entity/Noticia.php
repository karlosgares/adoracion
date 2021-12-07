<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoticiaRepository")
 */
class Noticia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaalta;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechabaja;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $portada;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $posicion;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechabaja(): ?\DateTimeInterface
    {
        return $this->fechabaja;
    }

    public function setFechabaja(?\DateTimeInterface $fechabaja): self
    {
        $this->fechabaja = $fechabaja;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getPortada(): ?bool
    {
        return $this->portada;
    }

    public function setPortada(bool $portada): self
    {
        $this->portada = $portada;

        return $this;
    }

    public function getFechaalta(): ?\DateTimeInterface
    {
        return $this->fechaalta;
    }

    public function setFechaalta(\DateTimeInterface $fechaalta): self
    {
        $this->fechaalta = $fechaalta;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(?string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $this->setFoto($fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/noticias';
        if (!is_dir($path))
            mkdir($path);
        return $path;
    }


    public static function getPosiciones()
    {
        $ret[0] = "Derecha del contenido";
        $ret[1] = "Izquierda del contenido";
        $ret[2] = "Encima del contenido";
        $ret[3] = "Debajo del contenido";
        return $ret;
    }

    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(?int $posicion): self
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function getFullfoto()
    {
        return $this->getTargetDirectory() . "/" . $this->getFoto();
    }

    /**
     * Devuelve la diferencia con hoy
     * @return string
     * @throws \Exception
     */
    public function getHace(): string
    {
        $now = new \DateTime('NOW');
        $diff = $this->getFechaalta()->diff($now)->format('%a');
        if ((int) $diff == 0) {
            $ret = "hoy";
        } else if ((int) $diff == 1) {
            $ret = sprintf("%s día", $diff);
        } else {
            $ret = sprintf("%s días", $diff);
        }
        return $ret;
    }

    /**
     * Devuelve true si debe salir en la web por activo y fechas
     * @return bool
     * @throws \Exception
     */
    public function inWeb(): bool
    {
        $now = new \DateTime('NOW');
        $bFechaAlta = (null !== $this->getFechaalta() || $now >=  $this->getFechaalta());
        $bFechaBaja = (null !==  $this->getFechabaja() || $now <=  $this->getFechabaja());

        return ($this->getActivo() && $bFechaAlta && $bFechaBaja);
    }
}
