<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="Cette address exist déjà, veuillez renseigner une autre addresse mail"
 * )
 */
class User implements UserInterface
{
    /**
     * @Assert\EqualTo(propertyPath="hash", message="confirmation pas valide")
     */
    public $passwordConfirm;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(   message = "vous devez renseigner votre prénom" )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(  message = "vous devez renseigner votre nom" )
     */
    private $lastName;

    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     * message="Veillez renseigner un email valide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(
     * message="vous devez renseigner une url valide pour votre avatar"
     * )
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *  min=10,
     *  minMessage="vous devez renseigner plus de 10 charactères"
     * )
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     * min=100,
     * minMessage="votre description doit faire plus de 100 charactères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="author")
     */
    private $ads;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

  

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

       /**
     * permet d'initialiser le slug
     *
     * @return void
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName.' '.$this->lastName);
        }
    }

    public function getRoles()
         {
             return ['ROLE_USER'];
         }

         public function getPassword(){
             return $this->hash;
         }

         public function getSalt(){
             /*pas la peine de l'implémenter, car l'algorithm bcrypt
             le contient déjà*/
         }

         public function getUsername(){
             return $this->email;
         }

         public function eraseCredentials(){
             /* toto*/
         }

         /**
          * @return Collection|Role[]
          */
         public function getUserRoles(): Collection
         {
             return $this->userRoles;
         }

         public function addUserRole(Role $userRole): self
         {
             if (!$this->userRoles->contains($userRole)) {
                 $this->userRoles[] = $userRole;
                 $userRole->addUser($this);
             }

             return $this;
         }

         public function removeUserRole(Role $userRole): self
         {
             if ($this->userRoles->contains($userRole)) {
                 $this->userRoles->removeElement($userRole);
                 $userRole->removeUser($this);
             }

             return $this;
         }
}

