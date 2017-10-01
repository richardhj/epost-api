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

namespace Richardhj\EPost\Api\Test\Metadata\Envelope\Recipient;

use Richardhj\EPost\Api\Exception\InvalidRecipientDataException;
use Richardhj\EPost\Api\Metadata\Envelope\Recipient\Normal as Recipient;


/**
 * Class NormalTest
 *
 * @package Richardhj\EPost\Api\Test\Metadata\Envelope\Recipient
 */
class NormalTest extends \PHPUnit_Framework_TestCase
{
    public function testFriendlyEmail()
    {
        $name          = 'Erika Mustermann';
        $email         = 'erika@example.com';
        $friendlyEmail = "$name <$email>";
        $recipient     = Recipient::createFromFriendlyEmail($friendlyEmail);

        $this->assertEquals($name, $recipient->getDisplayName());
        $this->assertEquals($email, $recipient->getEpostAddress());
    }

    public function testNoEpostAddress()
    {
        $recipient = new Recipient();
        $recipient->setDisplayName('Erika Mustermann');

        $json = json_encode($recipient);

        $this->expectException(InvalidRecipientDataException::class);
    }
}