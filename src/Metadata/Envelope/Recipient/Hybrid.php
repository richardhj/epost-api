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

use Richardhj\EPost\Api\Exception\InvalidRecipientDataException;
use Richardhj\EPost\Api\Metadata\Envelope\AbstractRecipient;


/**
 * Class Hybrid
 *
 * @method Hybrid setCompany($company)
 * @method Hybrid setSalutation($salutation)
 * @method Hybrid setTitle($title)
 * @method Hybrid setFirstName($firstName)
 * @method Hybrid setLastName($lastName)
 * @method Hybrid setStreetName($streetName)
 * @method Hybrid setHouseNumber($houseNumber)
 * @method Hybrid setAddressAddOn($addressAddOn)
 * @method Hybrid setPostOfficeBox($postOfficeBox)
 * @method Hybrid setZipCode($zipCode)
 * @method Hybrid setCity($city)
 * @method string getCompany()
 * @method string getSalutation()
 * @method string getTitle()
 * @method string getFirstName()
 * @method string getLastName()
 * @method string getStreetName()
 * @method string getHouseNumber()
 * @method string getAddressAddOn()
 * @method string getPostOfficeBox()
 * @method string getZipCode()
 * @method string getCity()
 *
 * @package Richardhj\EPost\Api\Metadata\Envelope\AbstractRecipient
 */
class Hybrid extends AbstractRecipient
{

    /**
     * Array containing all allowed properties with maximum allowed length
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
     * {@inheritdoc}
     */
    public function __set($key, $value)
    {
        if (!in_array($key, static::getConfigurableFields())) {
            throw new \InvalidArgumentException(sprintf('Property "%s" is not supported', $key));
        }

        if (strlen($value) > static::$validationLengthMap[$key]) {
            throw new \InvalidArgumentException(
                sprintf('Value of property "%s" exceeds maximum length of %u', $key, static::$validationLengthMap[$key])
            );
        }

        return parent::__set($key, $value);
    }


    /**
     * {@inheritdoc}
     */
    public static function getConfigurableFields()
    {
        return array_keys(static::$validationLengthMap);
    }


    /**
     * {@inheritdoc}
     *
     * @throws InvalidRecipientDataException
     */
    function jsonSerialize()
    {
        if (!((isset($this->streetName) || isset($this->postOfficeBox)) && isset($this->zipCode))) {
            throw new InvalidRecipientDataException(
                'A (street name or post office box) and zip code must be set at least'
            );
        }

        if (isset($this->streetName) && isset($this->postOfficeBox)) {
            throw new InvalidRecipientDataException('It must not be set a street name AND post office box');
        }

        return parent::jsonSerialize();
    }
}
