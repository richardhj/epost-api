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

namespace Richardhj\EPost\Api\Metadata;


/**
 * Interface MetadataInterface
 * @package Richardhj\EPost\Api\Metadata
 */
interface MetadataInterface extends \JsonSerializable
{

    /**
     * Letter type normal
     */
    const LETTER_TYPE_NORMAL = 'normal';


    /**
     * Letter type hybrid
     */
    const LETTER_TYPE_HYBRID = 'hybrid';


    /**
     * Get the MIME type for the json encoded document
     *
     * @return string
     */
    public static function getMimeType();
}
