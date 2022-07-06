<?php

namespace App\Entity;

use App\Repository\RoomImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomImageRepository::class)]
class RoomImage extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Room::class, cascade: ['persist', 'remove'], inversedBy: 'roomImages')]
    #[ORM\JoinColumn(nullable: true)]
    private $room;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }
}
