<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpresaRepository::class)
 */
class Empresa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
    private $reu;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $tipo='empresarial';// empresarial, division territorial


    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="empresa", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=ActorEconomico::class, inversedBy="empresas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $actoreconomico;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nit;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $representante;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $ci;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $estado='solicitud';//solicitud, revisando, aprobado, denegado

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $representanto_email;
    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $representanto_movil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="empresa")
     */
    private $user;


    public function __construct()
    {
    }


    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getReu()
    {
        return $this->reu;
    }

    /**
     * @param mixed $reu
     */
    public function setReu($reu): void
    {
        $this->reu = $reu;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param mixed $web
     */
    public function setWeb($web): void
    {
        $this->web = $web;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getActoreconomico()
    {
        return $this->actoreconomico;
    }

    /**
     * @param mixed $actoreconomico
     */
    public function setActoreconomico($actoreconomico): void
    {
        $this->actoreconomico = $actoreconomico;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * @param mixed $representante
     */
    public function setRepresentante($representante): void
    {
        $this->representante = $representante;
    }

    /**
     * @return mixed
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * @param mixed $ci
     */
    public function setCi($ci): void
    {
        $this->ci = $ci;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getRepresentantoEmail()
    {
        return $this->representanto_email;
    }

    /**
     * @param mixed $representanto_email
     */
    public function setRepresentantoEmail($representanto_email): void
    {
        $this->representanto_email = $representanto_email;
    }

    /**
     * @return mixed
     */
    public function getRepresentantoMovil()
    {
        return $this->representanto_movil;
    }

    /**
     * @param mixed $representanto_movil
     */
    public function setRepresentantoMovil($representanto_movil): void
    {
        $this->representanto_movil = $representanto_movil;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }



}
