<?php
/**
 * E-POSTBUSINESS API integration
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POSTBUSINESS
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Api\Metadata;


/**
 * Interface MetadataInterface
 * @package EPost\Api\Metadata
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
