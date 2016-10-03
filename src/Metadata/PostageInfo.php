<?php
/**
 * E-POSTBUSINESS API integration
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POSTBUSINESS
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Api\Metadata;


/**
 * Class PostageInfo
 * @package EPost\Api\Metadata
 */
class PostageInfo implements MetadataInterface
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
     * @var DeliveryOptions|array
     */
    protected $options = [];


    /**
     * Specify to send an electronic E‑POST letter
     *
     * @return self
     */
    public function setLetterTypeNormal()
    {
        return $this->setLetterType(self::LETTER_TYPE_NORMAL);
    }


    /**
     * Specify to send a printed E‑POST letter
     *
     * @return self
     */
    public function setLetterTypeHybrid()
    {
        return $this->setLetterType(self::LETTER_TYPE_HYBRID);
    }


    /**
     * Set the letter type
     *
     * @param string $letterType
     *
     * @return self
     */
    public function setLetterType($letterType)
    {
        if (!in_array($letterType, Envelope::getLetterTypeOptions())) {
            throw new \InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $letterType, __FUNCTION__)
            );
        }

        $this->letter['type'] = $letterType;

        return $this;
    }


    /**
     * Get the letter type
     *
     * @return string|null
     */
    public function getLetterType()
    {
        return $this->letter['type'];
    }


    /**
     * Set the letter size
     *
     * @param mixed $letterSize Number of pages (printed) or rounded integer size of the document in MB (electronic)
     *
     * @return self
     */
    public function setLetterSize($letterSize)
    {
        $this->letter['size'] = (int)$letterSize;

        return $this;
    }


    /**
     * Get the letter size
     *
     * @return int|null
     */
    public function getLetterSize()
    {
        return $this->letter['size'];
    }


    /**
     * Set delivery options
     *
     * @param DeliveryOptions $options
     *
     * @return self
     */
    public function setDeliveryOptions(DeliveryOptions $options)
    {
        $this->options = $options;

        return $this;
    }


    /**
     * Get the delivery options
     *
     * @return DeliveryOptions|array
     */
    public function getDeliveryOptions()
    {
        return $this->options;
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

        if ($this->options) {
            $return += [
                'options' => $this->options->getData(),
            ];
        }

        return $return;
    }
}
