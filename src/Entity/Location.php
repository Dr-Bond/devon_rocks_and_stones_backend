<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hiddenOn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="hiddenLocations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hiddenBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stone", inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stone;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="foundLocations")
     */
    private $foundBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clue", mappedBy="location")
     */
    private $clues;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $foundOn;

    public function __construct(Player $hiddenBy, Stone $stone, string $area)
    {
        $this->hiddenOn = new \DateTime();
        $this->hiddenBy = $hiddenBy;
        $this->stone = $stone;
        $this->stone->setStatus(Stone::STATUS_HIDDEN);
        $this->area = $area;
        $this->clues = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHiddenOn(): \DateTimeInterface
    {
        return $this->hiddenOn;
    }

    public function setHiddenOn(\DateTimeInterface $hiddenOn): self
    {
        $this->hiddenOn = $hiddenOn;
        return $this;
    }

    public function getHiddenBy(): Player
    {
        return $this->hiddenBy;
    }

    public function setHiddenBy(Player $hiddenBy): self
    {
        $this->hiddenBy = $hiddenBy;
        return $this;
    }

    public function getStone(): Stone
    {
        return $this->stone;
    }

    public function setStone(Stone $stone): self
    {
        $this->stone = $stone;
        return $this;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;
        return $this;
    }

    public function getFoundBy(): ?Player
    {
        return $this->foundBy;
    }

    public function setFoundBy(?Player $foundBy): self
    {
        $this->foundBy = $foundBy;
        return $this;
    }

    public function getClues(): Collection
    {
        return $this->clues;
    }

    public function getFoundOn(): ?\DateTimeInterface
    {
        return $this->foundOn;
    }

    public function setFoundOn(?\DateTimeInterface $foundOn): self
    {
        $this->foundOn = $foundOn;
        return $this;
    }
}
