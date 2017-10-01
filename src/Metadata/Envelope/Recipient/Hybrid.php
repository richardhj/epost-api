<?php

/**
 * This file is part of richardhj/epost-api.
 *
 * Copyright (c) 2015-2017 Richard Henkenjohann
 *
 * @package   richardhj/epost-api
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2015-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/epost-api/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\EPost\Api\Metadata\Envelope\Recipient;

use InvalidArgumentException;
use Richardhj\EPost\Api\Exception\InvalidRecipientDataException;
use Richardhj\EPost\Api\Metadata\Envelope\AbstractRecipient;


/**
 * Class Hybrid
 *
 * @package Richardhj\EPost\Api\Metadata\Envelope\Recipient
 */
final class Hybrid extends AbstractRecipient
{

    /**
     * Mapping allowed properties with maximum allowed length
     *
     * @var array
     */
    protected static $validationLengthMap = [
        'company'       => 80,
        'salutation'    => 10,
        'title'         => 30,
        'firstName'     => 30,
        'lastName'      => 30,
        'streetName'    => 50,
        'houseNumber'   => 10,
        'addressAddOn'  => 40,
        'postOfficeBox' => 10,
        'zipCode'       => 5,
        'city'          => 80,
    ];

    /**
     * @param string $company
     *
     * @return self
     */
    public function setCompany($company): Hybrid
    {
        self::validateSetLength('company', $company);
        $this->data['company'] = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->data['company'] ?? null;
    }

    /**
     * @param string $salutation
     *
     * @return self
     */
    public function setSalutation($salutation): Hybrid
    {
        self::validateSetLength('salutation', $salutation);
        $this->data['salutation'] = $salutation;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->data['salutation'] ?? null;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title): Hybrid
    {
        self::validateSetLength('title', $title);
        $this->data['title'] = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->data['title'] ?? null;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName($firstName): Hybrid
    {
        self::validateSetLength('firstName', $firstName);
        $this->data['firstName'] = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->data['firstName'] ?? null;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName($lastName): Hybrid
    {
        self::validateSetLength('lastName', $lastName);
        $this->data['lastName'] = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->data['lastName'] ?? null;
    }

    /**
     * @param string $streetName
     *
     * @return self
     */
    public function setStreetName($streetName): Hybrid
    {
        self::validateSetLength('streetName', $streetName);
        $this->data['streetName'] = $streetName;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreetName()
    {
        return $this->data['streetName'] ?? null;
    }

    /**
     * @param string $houseNumber
     *
     * @return self
     */
    public function setHouseNumber($houseNumber): Hybrid
    {
        self::validateSetLength('houseNumber', $houseNumber);
        $this->data['houseNumber'] = $houseNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->data['houseNumber'] ?? null;
    }

    /**
     * @param string $addressAddOn
     *
     * @return self
     */
    public function setAddressAddOn($addressAddOn): Hybrid
    {
        self::validateSetLength('addressAddOn', $addressAddOn);
        $this->data['addressAddOn'] = $addressAddOn;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressAddOn()
    {
        return $this->data['addressAddOn'] ?? null;
    }

    /**
     * @param string $postOfficeBox
     *
     * @return self
     */
    public function setPostOfficeBox($postOfficeBox): Hybrid
    {
        self::validateSetLength('postOfficeBox', $postOfficeBox);
        $this->data['postOfficeBox'] = $postOfficeBox;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostOfficeBox()
    {
        return $this->data['postOfficeBox'] ?? null;
    }

    /**
     * @param string $zipCode
     *
     * @return self
     */
    public function setZipCode($zipCode): Hybrid
    {
        self::validateSetLength('zipCode', $zipCode);
        $this->data['zipCode'] = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->data['zipCode'] ?? null;
    }

    /**
     * @param string $city
     *
     * @@return self
     */
    public function setCity($city): Hybrid
    {
        self::validateSetLength('city', $city);
        $this->data['city'] = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->data['city'] ?? null;
    }


    /**
     * {@inheritdoc}
     *
     * @throws InvalidRecipientDataException
     */
    function jsonSerialize()
    {
        if ((null === $this->getStreetName() && null === $this->getPostOfficeBox()) || null === $this->getZipCode()) {
            throw new InvalidRecipientDataException(
                'A (street name or post office box) and zip code must be set at least'
            );
        }

        if (null !== $this->getStreetName() && null !== $this->getPostOfficeBox()) {
            throw new InvalidRecipientDataException('It must not be set a street name AND post office box');
        }

        return parent::jsonSerialize();
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    private static function validateSetLength($key, $value)
    {
        if (strlen($value) > static::$validationLengthMap[$key]) {
            throw new InvalidArgumentException(
                sprintf('Value of property "%s" exceeds maximum length of %u', $key, static::$validationLengthMap[$key])
            );
        }
    }
}
