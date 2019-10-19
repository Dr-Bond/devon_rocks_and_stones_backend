<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="player", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLineOne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLineTwo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $postcode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stone", mappedBy="owner")
     */
    private $stones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="hiddenBy")
     */
    private $hiddenLocations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="foundBy")
     */
    private $foundLocations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clue", mappedBy="addedBy")
     */
    private $clues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="addedBy")
     */
    private $posts;

    public function __construct(User $user, string $firstName, string $surname,\DateTimeInterface $dateOfBirth, string $addressLineOne, string $city, string $county, string $postcode)
    {
        $this->user =$user;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->dateOfBirth = $dateOfBirth;
        $this->addressLineOne = $addressLineOne;
        $this->city = $city;
        $this->county = $county;
        $this->postcode = $postcode;
        $this->stones = new ArrayCollection();
        $this->hiddenLocations = new ArrayCollection();
        $this->foundLocations = new ArrayCollection();
        $this->clues = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getDateOfBirth(): \DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getAddressLineOne(): string
    {
        return $this->addressLineOne;
    }

    public function setAddressLineOne(string $addressLineOne): self
    {
        $this->addressLineOne = $addressLineOne;
        return $this;
    }

    public function getAddressLineTwo(): ?string
    {
        return $this->addressLineTwo;
    }

    public function setAddressLineTwo(?string $addressLineTwo): self
    {
        $this->addressLineTwo = $addressLineTwo;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCounty(): string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $this->county = $county;
        return $this;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function getAddress(): array
    {
        $address = [];
        $this->addressLineOne ? $address[] = $this->addressLineOne : null;
        $this->addressLineTwo ? $address[] = $this->addressLineTwo : null;
        $this->city ? $address[] = $this->city : null;
        $this->county ? $address[] = $this->county : null;
        $this->postcode ? $address[] = $this->postcode : null;

        return $address;
    }

    /**
     * @return Collection|Stone[]
     */
    public function getStones(): Collection
    {
        return $this->stones;
    }

    /**
     * @return Collection|Location[]
     */
    public function getHiddenLocations(): Collection
    {
        return $this->hiddenLocations;
    }

    /**
     * @return Collection|Location[]
     */
    public function getFoundLocations(): Collection
    {
        return $this->foundLocations;
    }

    /**
     * @return Collection|Clue[]
     */
    public function getClues(): Collection
    {
        return $this->clues;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
