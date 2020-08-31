<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Un compte a déjà été créé avec cette adresse mail")
 * @UniqueEntity(fields={"code_adhesion"}, message="code adhésion déjà utilisé")
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $piece_identite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_adhesion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_parrain;


    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="admin")
     */
    private $messages;

    /**
     * @ORM\Column(type="bigint")
     */
    private $compte_banquaire;

    /**
     * @ORM\Column(type="date")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=Formule::class, mappedBy="user")
     */
    private $formules;




    public function __construct()
    {
        $this->code_adhesion= $this->generateCodeadhesion();
        $this->messages = new ArrayCollection();
        $this->date_inscription = new DateTime();
        $this->formules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPieceIdentite(): ?string
    {
        return $this->piece_identite;
    }

    public function setPieceIdentite(string $piece_identite): self
    {
        $this->piece_identite = $piece_identite;

        return $this;
    }

    public function getCodeAdhesion(): ?string
    {
        return $this->code_adhesion;
    }

    public function setCodeAdhesion(string $code_adhesion): self
    {
        $this->code_adhesion = $code_adhesion;

        return $this;
    }

    public function getCodeParrain(): ?string
    {
        return $this->code_parrain;
    }

    public function setCodeParrain(?string $code_parrain): self
    {
        $this->code_parrain = $code_parrain;

        return $this;
    }

    /**
     * @param int $numAlpha
     * @return string
     */
    public function generateCodeadhesion($numAlpha=10):?string
    {
        $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return str_shuffle(
            substr(str_shuffle(uniqid($listAlpha)),0,$numAlpha)
        );
    }


    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAdmin($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAdmin() === $this) {
                $message->setAdmin(null);
            }
        }

        return $this;
    }

    public function getCompteBanquaire(): ?string
    {
        return $this->compte_banquaire;
    }

    public function setCompteBanquaire(string $compte_banquaire): self
    {
        $this->compte_banquaire = $compte_banquaire;

        return $this;
    }

    public function __toString()
    {
        return $this->code_adhesion;
    }

    public function getDateInscription($format = 'd-m-y')
    {
        return $this->date_inscription->format($format);
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection|Formule[]
     */
        public function getFormules(): Collection
    {
        return $this->formules;
    }

    public function addFormule(Formule $formule): self
    {
        if (!$this->formules->contains($formule)) {
            $this->formules[] = $formule;
            $formule->addUser($this);
        }

        return $this;
    }

    public function removeFormule(Formule $formule): self
    {
        if ($this->formules->contains($formule)) {
            $this->formules->removeElement($formule);
            $formule->removeUser($this);
        }

        return $this;
    }


}
