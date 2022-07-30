<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Hotel extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $rules = [];

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToOne(inversedBy: 'hotel', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToOne(mappedBy: 'hotel', targetEntity: Address::class, cascade: ['persist', 'remove'])]
    private $address;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: HotelImage::class, cascade: ['persist', 'remove'])]
    private $hotelImages;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Room::class, cascade: ['persist', 'remove'])]
    private $rooms;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    public function __construct()
    {
        $this->hotelImages = new ArrayCollection();
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
        $this->rooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
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

    public function getRules(): ?array
    {
        return $this->rules;
    }

    public function setRules(?array $rules): self
    {
        $this->rules = $rules ?? [];

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        // set the owning side of the relation if necessary
        if ($address->getHotel() !== $this) {
            $address->setHotel($this);
        }

        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, HotelImage>
     */
    public function getHotelImages(): Collection
    {
        return $this->hotelImages;
    }

    public function addHotelImage(HotelImage $hotelImage): self
    {
        if (!$this->hotelImages->contains($hotelImage)) {
            $this->hotelImages[] = $hotelImage;
            $hotelImage->setHotel($this);
        }

        return $this;
    }

    public function removeHotelImage(HotelImage $hotelImage): self
    {
        if ($this->hotelImages->removeElement($hotelImage)) {
            // set the owning side to null (unless already changed)
            if ($hotelImage->getHotel() === $this) {
                $hotelImage->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setHotel($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getHotel() === $this) {
                $room->setHotel(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
