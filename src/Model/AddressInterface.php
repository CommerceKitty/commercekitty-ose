<?php declare(strict_types=1);

namespace App\Model;

/**
 * @see https://en.wikipedia.org/wiki/Address
 */
interface AddressInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * @return string
     */
    public function getLastName(): ?string;

    /**
     * @return string
     */
    public function getCompanyName(): ?string;

    /**
     * @return string
     */
    public function getAddressOne(): ?string;

    /**
     * @return string
     */
    public function getAddressTwo(): ?string;

    /**
     * @return string
     */
    public function getState(): ?string;

    /**
     * @return string
     */
    public function getCity(): ?string;

    /**
     * @return string
     */
    public function getCounty(): ?string;

    /**
     * @return string
     */
    public function getPostalCode(): ?string;

    /**
     * @return string
     */
    public function getCountry(): ?string;

    /**
     * @return string
     */
    public function getCountryCode(): ?string;

    /**
     * @return float
     */
    public function getLatitude(): ?float;

    /**
     * @return float
     */
    public function getLongitude(): ?float;
}
