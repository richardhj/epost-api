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
use Richardhj\EPost\Api\Metadata\Envelope\Recipient\Hybrid as Recipient;


/**
 * Class HybridTest
 *
 * @package Richardhj\EPost\Api\Test\Metadata\Envelope\Recipient
 */
class HybridTest extends \PHPUnit_Framework_TestCase
{

    public function testValid()
    {
        $recipient = new Recipient();
        $recipient
            ->setFirstName('Erika')
            ->setLastName('Mustermann')
            ->setStreetName('Paulistr. 4')
            ->setZipCode('12345')
            ->setCity('Berlin')
        ;

        $this->assertTrue(!empty($recipient->getData()));
        $this->assertJson(json_decode($recipient));
    }


    public function testNoStreetNorPostBoxButZipCode()
    {
        $recipient = new Recipient();
        $recipient
            ->setFirstName('Erika')
            ->setLastName('Mustermann')
            ->setZipCode('12345')
            ->setCity('Berlin')
        ;

        $json = json_decode($recipient);

        $this->expectException(InvalidRecipientDataException::class);
    }

    public function testStreetNameButNoZipCode()
    {
        $recipient = new Recipient();
        $recipient
            ->setFirstName('Erika')
            ->setLastName('Mustermann')
            ->setStreetName('Paulistr. 5')
            ->setCity('Berlin')
        ;

        $json = json_decode($recipient);

        $this->expectException(InvalidRecipientDataException::class);
    }

    public function testPostOfficeBoxButNoZipCode()
    {
        $recipient = new Recipient();
        $recipient
            ->setFirstName('Erika')
            ->setLastName('Mustermann')
            ->setPostOfficeBox('123456')
            ->setCity('Berlin')
        ;

        $json = json_decode($recipient);

        $this->expectException(InvalidRecipientDataException::class);
    }

    public function testStreetNameAndPostOfficeBox()
    {
        $recipient = new Recipient();
        $recipient
            ->setFirstName('Erika')
            ->setLastName('Mustermann')
            ->setPostOfficeBox('123456')
            ->setStreetName('Paulistr. 5')
            ->setCity('Berlin')
        ;

        $json = json_decode($recipient);

        $this->expectException(InvalidRecipientDataException::class);
    }
}
