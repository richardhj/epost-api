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

use EPost\Api\Metadata\Envelope\AbstractRecipient;
use EPost\Api\Metadata\Envelope\Recipient;


/**
 * Class Envelope
 * @package EPost\Api\Metadata
 */
class Envelope implements MetadataInterface
{

    /**
     * The data for used for json encoding
     *
     * @var array
     */
    protected $data = [];


    /**
     * Specify to send an electronic E‑POST letter
     *
     * @return self
     */
    public function setSystemMessageTypeNormal()
    {
        return $this->setSystemMessageType(self::LETTER_TYPE_NORMAL);
    }


    /**
     * Specify to send a physical E‑POST letter
     *
     * @return self
     */
    public function setSystemMessageTypeHybrid()
    {
        return $this->setSystemMessageType(self::LETTER_TYPE_HYBRID);
    }


    /**
     * Specify the type of E-POST letter
     *
     * @param string $messageType
     *
     * @return self
     */
    public function setSystemMessageType($messageType)
    {
        if (!in_array($messageType, static::getLetterTypeOptions())) {
            throw new \InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $messageType, __FUNCTION__)
            );
        }

        $this->data['letterType']['systemMessageType'] = $messageType;

        return $this;
    }


    /**
     * Get the system message type
     *
     * @return string
     */
    public function getSystemMessageType()
    {
        return $this->data['letterType']['systemMessageType'] ?: self::LETTER_TYPE_NORMAL;
    }


    /**
     * Add a normal (electronic) recipient
     *
     * @param Recipient\Normal|AbstractRecipient $recipient
     *
     * @return self
     */
    public function addRecipientNormal(Recipient\Normal $recipient)
    {
        if ($this->isHybridLetter()) {
            throw new \LogicException(
                sprintf('Can not set recipients if message type is "%s"', self::LETTER_TYPE_HYBRID)
            );
        }

        $this->data['recipients'][] = $recipient;

        return $this;
    }


    /**
     * Add a hybrid recipient for printed letters
     *
     * @param Recipient\Hybrid|AbstractRecipient $recipient
     *
     * @return self
     */
    public function addRecipientPrinted(Recipient\Hybrid $recipient)
    {
        if ($this->isNormalLetter()) {
            throw new \LogicException(
                sprintf('Can not set recipientsPrinted if message type is "%s"', self::LETTER_TYPE_NORMAL)
            );
        }

        if (count($this->getRecipients())) {
            throw new \LogicException('It must not be set more than one printed recipient');
        }

        $this->data['recipientsPrinted'][] = $recipient;

        return $this;
    }


    /**
     * Get the recipients added to the envelope
     *
     * @return AbstractRecipient[]
     */
    public function getRecipients()
    {
        switch ($this->getSystemMessageType()) {
            case self::LETTER_TYPE_NORMAL:
                return $this->data['recipients'];
                break;

            case self::LETTER_TYPE_HYBRID:
                return $this->data['recipientsPrinted'];
                break;
        }

        return null;
    }


    /**
     * Set the subject of the E‑POST letter
     *
     * @param string $subject
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->data['subject'] = $subject;

        return $this;
    }


    /**
     * Get the subject of the E‑POST letter
     *
     * @return string|null
     */
    public function getSubject()
    {
        return $this->data['subject'];
    }


    /**
     * Check whether the letter will be carried out electronic
     *
     * @return bool
     */
    public function isNormalLetter()
    {
        return (self::LETTER_TYPE_NORMAL === $this->getSystemMessageType());
    }


    /**
     * Check whether the letter will be carried out printed
     *
     * @return bool
     */
    public function isHybridLetter()
    {
        return (self::LETTER_TYPE_HYBRID === $this->getSystemMessageType());
    }


    /**
     * Get all options that can be used for setSystemMessageType() or similar
     *
     * @return array
     */
    public static function getLetterTypeOptions()
    {
        return [
            self::LETTER_TYPE_NORMAL,
            self::LETTER_TYPE_HYBRID,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function getMimeType()
    {
        return 'application/vnd.epost-letter+json';
    }


    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return ['envelope' => $this->data];
    }
}
