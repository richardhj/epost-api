<?php
/**
 * E-POSTBUSINESS API integration
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POSTBUSINESS
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Api\Metadata\Envelope\Recipient;

use EPost\Api\Exception\InvalidRecipientDataException;
use EPost\Api\Metadata\Envelope\AbstractRecipient;


/**
 * Class Normal
 *
 * @method Normal setDisplayName($displayName)
 * @method Normal setEpostAddress($epostAddress)
 * @method string getDisplayName()
 * @method string getEpostAddress()
 *
 * @package EPost\Api\Metadata\Envelope\AbstractRecipient
 */
class Normal extends AbstractRecipient
{

    /**
     * {@inheritdoc}
     */
    protected static $configurableFields = [
        'displayName',
        'epostAddress',
    ];


    /**
     * {@inheritdoc}
     */
    public function __set($key, $value)
    {
        if (in_array($key, static::getConfigurableFields())) {
            return parent::__set($key, $value);
        }

        throw new \InvalidArgumentException(sprintf('Property "%s" is not allowed to set', $key));
    }


    /**
     * Create an instance by given (friendly) email string
     * A friendly email includes the display name and is formatted like "John Doe <doe@example.org>"
     *
     * @param string $email
     *
     * @return self
     */
    public static function createFromFriendlyEmail($email)
    {
        $recipient = new self;

        list($displayName, $epostAddress) = self::splitFriendlyEmail($email);

        if (strlen($displayName)) {
            $recipient->setDisplayName($displayName);
        }

        $recipient->setEpostAddress($epostAddress);

        return $recipient;
    }


    /**
     * Alias for createFromFriendlyEmail
     *
     * @param string $email
     *
     * @return self
     */
    public static function createFromEmail($email)
    {
        return static::createFromFriendlyEmail($email);
    }


    /**
     * Split a friendly-name e-address and return name and e-mail as array
     *
     * @author Leo Feyer <https://github.com/leofeyer> for Contao Open Source CMS <https://github.com/contao>
     *
     * @param string $email A friendly-name e-mail address
     *
     * @return array An array with name and e-mail address
     */
    private static function splitFriendlyEmail($email)
    {
        if (false !== strpos($email, '<')) {
            return array_map('trim', explode(' <', str_replace('>', '', $email)));
        } elseif (false !== strpos($email, '[')) {
            return array_map('trim', explode(' [', str_replace(']', '', $email)));
        } else {
            return ['', $email];
        }
    }


    /**
     * {@inheritdoc}
     *
     * @throws InvalidRecipientDataException
     */
    function jsonSerialize()
    {
        if (!isset($this->epostAddress)) {
            throw new InvalidRecipientDataException('No E-POST address is set');
        }

        return parent::jsonSerialize();
    }
}
