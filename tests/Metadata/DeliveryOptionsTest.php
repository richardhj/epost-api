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


/**
 * Class DeliveryOptionsTest
 *
 * @package Richardhj\EPost\Api\Test\Metadata
 */
class DeliveryOptionsTest extends TestCase
{

    public function testJsonArray()
    {
        $deliveryOptions = new DeliveryOptions();
        $deliveryOptions->setColorColored();

        $data = $deliveryOptions->jsonSerialize();

        $this->assertArrayHasKey('options', $data);
    }
}
