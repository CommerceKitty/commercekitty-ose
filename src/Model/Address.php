<?php declare(strict_types=1);

namespace App\Model;

class Address implements AddressInterface
{
    protected $id;
    protected $phone;
    protected $firstName;
    protected $lastName;
    protected $companyName;
    protected $addressOne;
    protected $addressTwo;
    protected $state;
    protected $city;
    protected $county;
    protected $postalCode;
    protected $country;
    protected $countryCode;
    protected $latitude;
    protected $longitude;

    /**
     * @todo This should be the formatted address without HTML code
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) trim($this->firstName.' '.$this->lastName);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     */
    public function setCompanyName(string $name): self
    {
        $this->companyName = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressOne(): ?string
    {
        return $this->addressOne;
    }

    /**
     */
    public function setAddressOne(string $address): self
    {
        $this->addressOne = $address;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressTwo(): ?string
    {
        return $this->addressTwo;
    }

    /**
     */
    public function setAddressTwo(string $address): self
    {
        $this->addressTwo = $address;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCounty(): ?string
    {
        return $this->country;
    }

    /**
     */
    public function setCounty(string $county): self
    {
        $this->county = $county;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     */
    public function setCountryCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     */
    public function setLatitude(float $lat): self
    {
        $this->latitude = $lat;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     */
    public function setLongitude(float $long): self
    {
        $this->longitude = $long;

        return $this;
    }
}
