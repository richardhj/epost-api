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


namespace Richardhj\EPost\Api\Test;

use GuzzleHttp\Exception\BadResponseException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Richardhj\EPost\Api\Exception\MissingAccessTokenException;
use Richardhj\EPost\Api\Exception\MissingAttachmentException;
use Richardhj\EPost\Api\Exception\MissingEnvelopeException;
use Richardhj\EPost\Api\Exception\MissingRecipientException;
use Richardhj\EPost\Api\Letter;
use Richardhj\EPost\Api\Metadata\Envelope;


/**
 * Class LetterTest
 *
 * @package Richardhj\EPost\Api\Test
 */
class LetterTest extends TestCase
{
    public function testMissingEnvelope()
    {
        $this->expectException(MissingEnvelopeException::class);

        $letter = new Letter();
        $letter->setTestEnvironment(true);

        $letter->create();
    }

    public function testMissingRecipients()
    {
        $this->expectException(MissingRecipientException::class);

        $letter = new Letter();
        $letter
            ->setTestEnvironment(true)
            ->setEnvelope((new Envelope()));

        $letter->create();
    }

    public function testMissingAttachment()
    {
        $this->expectException(MissingAttachmentException::class);

        $letter = new Letter();
        $letter
            ->setTestEnvironment(true)
            ->setEnvelope(
                (new Envelope())->setSystemMessageTypeHybrid()->addRecipientPrinted(
                    (new Envelope\Recipient\Hybrid())
                        ->setFirstName('Erika')
                        ->setLastName('Mustermann')
                        ->setStreetName('Paulistr. 5')
                        ->setZipCode('12345')
                        ->setCity('Berlin')
                )
            );

        $letter->create();
    }

    public function testMissingAccessToken()
    {
        $this->expectException(MissingAccessTokenException::class);

        $letter = new Letter();
        $letter
            ->setTestEnvironment(true)
            ->setEnvelope(
                (new Envelope())->setSystemMessageTypeHybrid()->addRecipientPrinted(
                    (new Envelope\Recipient\Hybrid())
                        ->setFirstName('Erika')
                        ->setLastName('Mustermann')
                        ->setStreetName('Paulistr. 5')
                        ->setZipCode('12345')
                        ->setCity('Berlin')
                )
            )
            ->addAttachment(__FILE__);

        $letter->create();
    }

    public function testInvalidAccessToken()
    {
        $this->expectException(BadResponseException::class);

        $letter = new Letter();
        $letter
            ->setTestEnvironment(true)
            ->setEnvelope(
                (new Envelope())->setSystemMessageTypeHybrid()->addRecipientPrinted(
                    (new Envelope\Recipient\Hybrid())
                        ->setFirstName('Erika')
                        ->setLastName('Mustermann')
                        ->setStreetName('Paulistr. 5')
                        ->setZipCode('12345')
                        ->setCity('Berlin')
                )
            )
            ->addAttachment(__FILE__)
            ->setAccessToken((new AccessToken(['access_token' => 'test'])));

        $letter->create();
    }
}
