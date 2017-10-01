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

namespace Richardhj\EPost\Api\Metadata\Envelope;

use JsonSerializable;


/**
 * Class AbstractRecipient
 *
 * @package Richardhj\EPost\Api\Metadata\Envelope
 */
abstract class AbstractRecipient implements JsonSerializable
{

    /**
     * The data used for json
     *
     * @var array
     */
    protected $data = [];

    /**
     * Get raw data array
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *{@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->getData();
    }
}
