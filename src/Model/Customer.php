<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
class Customer implements CustomerInterface
{
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $phone;
    protected $shippingAddress;
    protected $billingAddress;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) trim($this->firstName.' '.$this->lastName);
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     */
    public function setFirstName(string $name): self
    {
        $this->firstName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     */
    public function setLastName(string $name): self
    {
        $this->lastName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return AddressInterface|null
     */
    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    /**
     */
    public function setShippingAddress(AddressInterface $address): self
    {
        $this->shippingAddress = $address;

        return $this;
    }

    /**
     * @return AddressInterface|null
     */
    public function getBillingAddress(): ?AddressInterface
    {
        return $this->billingAddress;
    }

    /**
     */
    public function setBillingAddress(AddressInterface $address): self
    {
        $this->billingAddress = $address;

        return $this;
    }
}
