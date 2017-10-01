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

namespace Richardhj\EPost\Api\Metadata;

use InvalidArgumentException;


/**
 * Class PostageInfo
 *
 * @package Richardhj\EPost\Api\Metadata
 */
final class PostageInfo implements MetadataInterface
{

    /**
     * The letter properties
     *
     * @var array
     */
    protected $letter = [];

    /**
     * The delivery options
     *
     * @var DeliveryOptions
     */
    protected $deliveryOptions;

    /**
     * Specify to send an electronic E‑POST letter
     *
     * @return self
     */
    public function setLetterTypeNormal(): PostageInfo
    {
        return $this->setLetterType(self::LETTER_TYPE_NORMAL);
    }

    /**
     * Specify to send a printed E‑POST letter
     *
     * @return self
     */
    public function setLetterTypeHybrid(): PostageInfo
    {
        return $this->setLetterType(self::LETTER_TYPE_HYBRID);
    }

    /**
     * Set the letter type
     *
     * @param string $letterType
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function setLetterType($letterType): PostageInfo
    {
        if (!in_array($letterType, Envelope::getLetterTypeOptions())) {
            throw new InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $letterType, __FUNCTION__)
            );
        }

        $this->letter['type'] = $letterType;

        return $this;
    }

    /**
     * Get the letter type
     *
     * @return string
     */
    public function getLetterType()
    {
        return $this->letter['type'] ?? null;
    }

    /**
     * Set the letter size
     *
     * @param mixed $letterSize Number of pages (printed) or rounded integer size of the document in MB (electronic)
     *
     * @return self
     */
    public function setLetterSize($letterSize): PostageInfo
    {
        $this->letter['size'] = (int)$letterSize;

        return $this;
    }

    /**
     * Get the letter size
     *
     * @return int
     */
    public function getLetterSize()
    {
        return $this->letter['size'] ?? null;
    }

    /**
     * Set delivery options
     *
     * @param DeliveryOptions $options
     *
     * @return self
     */
    public function setDeliveryOptions(DeliveryOptions $options): PostageInfo
    {
        $this->deliveryOptions = $options;

        return $this;
    }

    /**
     * Get the delivery options
     *
     * @return DeliveryOptions|array
     */
    public function getDeliveryOptions()
    {
        return $this->deliveryOptions;
    }

    /**
     * Check whether the letter will be carried out electronic
     *
     * @return bool
     */
    public function isNormalLetter()
    {
        return (self::LETTER_TYPE_NORMAL === $this->getLetterType());
    }

    /**
     * Check whether the letter will be carried out printed
     *
     * @return bool
     */
    public function isHybridLetter()
    {
        return (self::LETTER_TYPE_HYBRID === $this->getLetterType());
    }

    /**
     * {@inheritdoc}
     */
    public static function getMimeType()
    {
        return 'application/vnd.epost-postage-info+json';
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        $return = [
            'letter' => $this->letter,
        ];

        if (null !== $this->deliveryOptions) {
            $return += [
                'options' => $this->deliveryOptions->getData(),
            ];
        }

        return $return;
    }
}
