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

namespace Richardhj\EPost\Api\Test\Metadata;

use LogicException;
use Richardhj\EPost\Api\Metadata\Envelope;


/**
 * Class EnvelopeTest
 *
 * @package Richardhj\EPost\Api\Test\Metadata
 */
class EnvelopeTest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidMultipleRecipientsOnHybridLetter()
    {
        $envelope = new Envelope();
        $envelope->setSystemMessageTypeHybrid();

        $recipients = [
            (new Envelope\Recipient\Hybrid())->setFirstName('Erika')->setLastName('Mustermann'),
            (new Envelope\Recipient\Hybrid())->setFirstName('Max')->setLastName('Mustermann'),
        ];

        foreach ($recipients as $recipient) {
            $envelope->addRecipientPrinted($recipient);
        }

        $this->expectException(LogicException::class);
    }
}
