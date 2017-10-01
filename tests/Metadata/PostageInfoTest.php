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

use PHPUnit\Framework\TestCase;
use Richardhj\EPost\Api\Metadata\DeliveryOptions;
use Richardhj\EPost\Api\Metadata\PostageInfo;


/**
 * Class PostageInfoTest
 *
 * @package Richardhj\EPost\Api\Test\Metadata
 */
class PostageInfoTest extends TestCase
{

    public function testHasOptions()
    {
        $deliveryOptions = new DeliveryOptions();
        $deliveryOptions
            ->setColorColored()
            ->setDuplex(true);

        $postageInfo = new PostageInfo();
        $postageInfo
            ->setLetterTypeHybrid()
            ->setDeliveryOptions($deliveryOptions);

        $data = $postageInfo->jsonSerialize();

        $this->assertArrayHasKey('letter', $data);
        $this->assertArrayHasKey('options', $data);
    }

    public function testHasNoOptions()
    {
        $postageInfo = new PostageInfo();
        $postageInfo
            ->setLetterTypeHybrid();

        $data = $postageInfo->jsonSerialize();

        $this->assertArrayHasKey('letter', $data);
        $this->assertArrayNotHasKey('options', $data);
    }
}
