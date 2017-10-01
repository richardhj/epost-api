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
 * Class Normal
 *
 * @package Richardhj\EPost\Api\Metadata\Envelope\Recipient
 */
final class Normal extends AbstractRecipient
{

    /**
     * @param string $displayName
     *
     * @return self
     */
    public function setDisplayName($displayName): Normal
    {
        $this->data['displayName'] = $displayName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->data['displayName'] ?? null;
    }

    /**
     * @param string $epostAddress
     *
     * @return Normal
     */
    public function setEpostAddress($epostAddress): Normal
    {
        $this->data['epostAddress'] = $epostAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getEpostAddress()
    {
        return $this->data['epostAddress'] ?? null;
    }

    /**
     * Create an instance by given (friendly) email string
     * A friendly email includes the display name and is formatted like "John Doe <doe@example.org>"
     *
     * @param string $email
     *
     * @return self
     */
    public static function createFromFriendlyEmail($email): Normal
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
    public static function createFromEmail($email): Normal
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
        if (null === $this->getEpostAddress()) {
            throw new InvalidRecipientDataException('No E-POST address is set');
        }

        return parent::jsonSerialize();
    }
}
