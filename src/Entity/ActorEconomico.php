<?php

namespace App\Entity;

use App\Repository\ActorEconomicoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActorEconomicoRepository::class)
 */
class ActorEconomico
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;
    /**
     * @ORM\Column(type="string", length=25)
     */
    private $siglas;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forma;
    /**
     * @ORM\OneToMany(targetEntity=Empresa::class, mappedBy="actoreconomico")
     */
    private $empresas;

    public function __construct()
    {
        $this->empresas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getSiglas()
    {
        return $this->siglas;
    }

    /**
     * @param mixed $siglas
     */
    public function setSiglas($siglas): void
    {
        $this->siglas = $siglas;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getForma()
    {
        return $this->forma;
    }

    /**
     * @param mixed $forma
     */
    public function setForma($forma): void
    {
        $this->forma = $forma;
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

}
