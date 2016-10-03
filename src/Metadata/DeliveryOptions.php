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
 * Class DeliveryOptions
 * @package EPost\Api\Metadata
 */
class DeliveryOptions implements MetadataInterface
{

    /**
     * Color grayscale
     */
    const OPTION_COLOR_GRAYSCALE = 'grayscale';


    /**
     * Color colored
     */
    const OPTION_COLOR_COLORED = 'colored';


    /**
     * Cover letter included
     */
    const OPTION_COVER_LETTER_INCLUDED = 'included';


    /**
     * Cover letter generate
     */
    const OPTION_COVER_LETTER_GENERATE = 'generate';


    /**
     * Registered standard
     */
    const OPTION_REGISTERED_STANDARD = 'standard';


    /**
     * Registered submission only
     */
    const OPTION_REGISTERED_SUBMISSION_ONLY = 'submissionOnly';


    /**
     * Registered addressee only
     */
    const OPTION_REGISTERED_ADDRESSEE_ONLY = 'addresseeOnly';


    /**
     * Registered with return receipt
     */
    const OPTION_REGISTERED_WITH_RETURN_RECEIPT = 'withReturnReceipt';


    /**
     * Registered addressee only with return receipt
     */
    const OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT = 'addresseeOnlyWithReturnReceipt';


    /**
     * Registered no
     */
    const OPTION_REGISTERED_NO = 'no';


    /**
     * The data used for json encoding
     *
     * @var array
     */
    protected $data = [];


    /**
     * The option specifies to carry out a black-and-white printing
     *
     * @return self
     */
    public function setColorGrayscale()
    {
        return $this->setColor(self::OPTION_COLOR_GRAYSCALE);
    }


    /**
     * The option specifies to carry out a color printing
     *
     * @return self
     */
    public function setColorColored()
    {
        return $this->setColor(self::OPTION_COLOR_COLORED);
    }


    /**
     * The option specifies whether a color or black-and-white printing is carried out
     *
     * @param string $color
     *
     * @return self
     */
    public function setColor($color)
    {
        if (!in_array($color, static::getOptionsForColor())) {
            throw new \InvalidArgumentException(sprintf('Property %s is not supported for %s', $color, __FUNCTION__));
        }

        $this->data['color'] = $color;

        return $this;
    }


    /**
     * Get color property
     *
     * @return string
     */
    public function getColor()
    {
        return $this->data['color'] ?: self::OPTION_COLOR_GRAYSCALE;
    }


    /**
     * The first page of the submitted PDF will be used as the cover letter
     *
     * @return self
     */
    public function setCoverLetterIncluded()
    {
        return $this->setCoverLetter(self::OPTION_COVER_LETTER_INCLUDED);
    }


    /**
     * The cover letter is automatically generated
     *
     * @return self
     */
    public function setCoverLetterGenerate()
    {
        return $this->setCoverLetter(self::OPTION_COVER_LETTER_GENERATE);
    }


    /**
     * The option specifies whether a cover letter is generated for delivery or if it is included in the PDF attachment
     *
     * @param string $coverLetter
     *
     * @return self
     */
    public function setCoverLetter($coverLetter)
    {
        if (!in_array($coverLetter, static::getOptionsForCoverLetter())) {
            throw new \InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $coverLetter, __FUNCTION__)
            );
        }

        $this->data['coverLetter'] = $coverLetter;

        return $this;
    }


    /**
     * Get coverLetter property
     *
     * @return string
     */
    public function getCoverLetter()
    {
        return $this->data['coverLetter'] ?: self::OPTION_COVER_LETTER_GENERATE;
    }


    /**
     * The option specifies whether a double-sided duplex printing is to be used. When duplex printing is used, all
     * attached documents, including the generated cover page, are printed on both sides of a sheet.
     *
     * @param bool $duplex
     *
     * @return $this
     */
    public function setDuplex($duplex)
    {
        $this->data['duplex'] = (bool)$duplex;

        return $this;
    }


    /**
     * Get duplex property
     *
     * @return bool
     */
    public function getDuplex()
    {
        return $this->data['duplex'] ? true : false;
    }


    /**
     * “Einschreiben ohne Optionen” (registered mail without options)
     * Not only the recipient personally, but also an authorized recipient, e.g. a spousemust, is allowed to
     * acknowledge receipt.
     *
     * @return self
     */
    public function setRegisteredStandard()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_STANDARD);
    }


    /**
     * “Einschreiben Einwurf” (registered mail delivered to mailbox)
     * The deliverer of the Deutsche Post AG drops the letter into a mailbox of the receiver and the deliverer confirms
     * this with his signature.
     *
     * @return self
     */
    public function setRegisteredSubmissionOnly()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_SUBMISSION_ONLY);
    }


    /**
     * “Einschreiben nur mit Option Eigenhändig” (personal registered mail)
     * Only the recipient is allowed to acknowledge receipt.
     *
     * @return self
     */
    public function setRegisteredAddresseeOnly()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_ADDRESSEE_ONLY);
    }


    /**
     * “Einschreiben nur mit Option Rückschein” (registered mail with return receipt)
     * The sender gets sent the handwritten conformation of an authorized recipient about the delivery as original.
     *
     * @return self
     */
    public function setRegisteredWithReturnReceipt()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_WITH_RETURN_RECEIPT);
    }


    /**
     * “Einschreiben mit Option Eigenhändig und Rückschein” (personal registered mail with return receipt)
     * The sender gets sent the handwritten conformation of the recipient personally about the delivery as original
     *
     * @return self
     */
    public function setRegisteredAddresseeOnlyWithReturnReceipt()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT);
    }


    /**
     * “Standardbrief” (standard letter)
     *
     * @return self
     */
    public function setRegisteredNo()
    {
        return $this->setRegistered(self::OPTION_REGISTERED_NO);
    }


    /**
     * The option specifies if the E‑POST letter is sent as a “Einschreiben” (registered letter), and, if so, which
     * registered letter type is to be selected
     *
     * @param string $registered
     *
     * @return self
     */
    public function setRegistered($registered)
    {
        if (!in_array($registered, static::getOptionsForRegistered())) {
            throw new \InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $registered, __FUNCTION__)
            );
        }

        $this->data['registered'] = $registered;

        return $this;
    }


    /**
     * Get registered property
     *
     * @return string
     */
    public function getRegistered()
    {
        return $this->data['registered'] ?: self::OPTION_REGISTERED_NO;
    }


    /**
     * The option specifies whether an “Adressanreicherung” (address updating and enhancement) is to be carried out
     *
     * @param bool $tryElectronic
     *
     * @return self
     */
    public function setTryElectronic($tryElectronic)
    {
        $this->data['tryElectronic'] = (bool)$tryElectronic;

        return $this;
    }


    /**
     * Get tryElectronic property
     *
     * @return bool
     */
    public function getTryElectronic()
    {
        return $this->data['tryElectronic'] ? true : false;
    }


    /**
     * Get all options that can be used for setColor()
     *
     * @return array
     */
    public static function getOptionsForColor()
    {
        return [
            self::OPTION_COLOR_GRAYSCALE,
            self::OPTION_COLOR_COLORED,
        ];
    }


    /**
     * Get all options that can be used for setCoverLetter()
     *
     * @return array
     */
    public static function getOptionsForCoverLetter()
    {
        return [
            self::OPTION_COVER_LETTER_GENERATE,
            self::OPTION_COVER_LETTER_INCLUDED,
        ];
    }


    /**
     * Get all options that can be used for setRegistered()
     *
     * @return array
     */
    public static function getOptionsForRegistered()
    {
        return [
            self::OPTION_REGISTERED_STANDARD,
            self::OPTION_REGISTERED_SUBMISSION_ONLY,
            self::OPTION_REGISTERED_ADDRESSEE_ONLY,
            self::OPTION_REGISTERED_WITH_RETURN_RECEIPT,
            self::OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT,
            self::OPTION_REGISTERED_NO,
        ];
    }


    /**
     * Get the array containing all delivery options
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * {@inheritdoc}
     */
    public static function getMimeType()
    {
        return 'application/vnd.epost-dispatch-options+json';
    }


    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return ['options' => $this->data];
    }
}
