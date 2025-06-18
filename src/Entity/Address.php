<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SteetName;

    /**
     * @ORM\Column(type="integer")
     */
    private $PostCode;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSteetName(): ?string
    {
        return $this->SteetName;
    }

    public function setSteetName(string $SteetName): self
    {
        $this->SteetName = $SteetName;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->PostCode;
    }

    public function setPostCode(int $PostCode): self
    {
        $this->PostCode = $PostCode;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->Country;
    }

    public function setCountry(?Country $Country): self
    {
        $this->Country = $Country;

        return $this;
    }
}
