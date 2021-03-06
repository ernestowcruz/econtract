<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLES_SISTEMA = ['ROLE_USER'=>'ROLE_USER','ROLE_ADMIN'=>'ROLE_ADMIN','ROLE_ADMIN_EMPRESA'=>'ROLE_ADMIN_EMPRESA',
        'ROLE_ADMIN_DIVISION'=>'ROLE_ADMIN_DIVISION'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRegistered;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passold;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Nombre_apellidos;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link_acceso;

    /**
     * @ORM\OneToMany(targetEntity=Invitaciones::class, mappedBy="usuario")
     */
    private $invitaciones;

    public function __construct()
    {
        $this->isVerified = true;
        $this->dateRegistered = new \DateTime('now');
        $this->empresas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUserIdentifier();// TODO: Implement __toString() method.
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return mixed
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * @param mixed $dateRegistered
     */
    public function setDateRegistered($dateRegistered): void
    {
        $this->dateRegistered = $dateRegistered;
    }




    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPassold(): ?string
    {
        return $this->passold;
    }

    public function setPassold(?string $passold): self
    {
        $this->passold = $passold;

        return $this;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $Nombre_apellidos
     */
    public function setNombreApellidos($Nombre_apellidos): void
    {
        $this->Nombre_apellidos = $Nombre_apellidos;
    }

    /**
     * @return mixed
     */
    public function getNombreApellidos()
    {
        return $this->Nombre_apellidos;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa): void
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getLinkAcceso()
    {
        if ($this->link_acceso == '')
            return 'asasa';
        return $this->link_acceso;
    }

    /**
     * @param mixed $link_acceso
     */
    public function setLinkAcceso($link_acceso): void
    {
        $this->link_acceso = $link_acceso;
    }

    /**
     * @return mixed
     */
    public function getInvitaciones()
    {
        return $this->invitaciones;
    }

    /**
     * @param mixed $invitaciones
     */
    public function setInvitaciones($invitaciones): void
    {
        $this->invitaciones = $invitaciones;
    }
    /**
     * @return mixed
     */
    public function getInvitacionesActivas()
    {
        $aItems = array();
        foreach ($this->invitaciones as $invitacion)    {
            if ($invitacion->getEstado()=='enviada' or is_null($invitacion->getEstado()))
                $aItems[]= $invitacion;
        }

        return $aItems;
    }

}
