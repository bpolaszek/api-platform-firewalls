<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"order:read"}},
 *     denormalizationContext={"groups"={"order:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table("`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"employee:order:read", "customer:order:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"employee:order:read", "customer:order:read"})
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"employee:order:read"})
     */
    private $numberOfCalls = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getNumberOfCalls(): ?int
    {
        return $this->numberOfCalls;
    }

    public function setNumberOfCalls(int $numberOfCalls): self
    {
        $this->numberOfCalls = $numberOfCalls;

        return $this;
    }
}
