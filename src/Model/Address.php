<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Address implements AddressInterface, PayloadableInterface
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

    public function setId(?string $id): self
    {
        if ($this->id) {
            throw new \Exception('Cannot set ID after already set');
        }

        $this->id = $id;

        return $this;
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
    public function setPhone(?string $phone): self
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
    public function setFirstName(?string $name): self
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
    public function setLastName(?string $name): self
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
    public function setCompanyName(?string $name): self
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
    public function setAddressOne(?string $address): self
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
    public function setAddressTwo(?string $address): self
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
    public function setState(?string $state): self
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
    public function setCity(?string $city): self
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
    public function setCounty(?string $county): self
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
    public function setPostalCode(?string $postalCode): self
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
    public function setCountry(?string $country): self
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
    public function setCountryCode(?string $code): self
    {
        $this->countryCode = $code;

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
    public function setLatitude(?float $lat): self
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
    public function setLongitude(?float $long): self
    {
        $this->longitude = $long;

        return $this;
    }

    /**
     */
    public function toPayload(): array
    {
        return [
            'id'           => $this->id,
            'phone'        => $this->phone,
            'first_name'   => $this->firstName,
            'last_name'    => $this->lastName,
            'company_name' => $this->companyName,
            'address_one'  => $this->addressOne,
            'address_two'  => $this->addressTwo,
            'state'        => $this->state,
            'city'         => $this->city,
            'county'       => $this->county,
            'postal_code'  => $this->postalCode,
            'country'      => $this->country,
            'country_code' => $this->countryCode,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
        ];
    }

    /**
     */
    public static function fromPayload(array $payload): AddressInterface
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $model = new static();

        foreach ($payload as $k => $v) {
            $propertyAccessor->setValue($model, $k, $v);
        }

        return $model;
    }
}
