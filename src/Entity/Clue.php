<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClueRepository")
 */
class Clue
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
    private $addedOn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="clues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $addedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="clues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    private $content;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    public function __construct(Player $addedBy, Location $location, string $content)
    {
        $this->addedOn = new \DateTime();
        $this->addedBy = $addedBy;
        $this->location = $location;
        $this->content = $content;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddedOn(): \DateTimeInterface
    {
        return $this->addedOn;
    }

    public function setAddedOn(\DateTimeInterface $addedOn): self
    {
        $this->addedOn = $addedOn;
        return $this;
    }

    public function getAddedBy(): Player
    {
        return $this->addedBy;
    }

    public function setAddedBy(Player $addedBy): self
    {
        $this->addedBy = $addedBy;
        return $this;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }
}
