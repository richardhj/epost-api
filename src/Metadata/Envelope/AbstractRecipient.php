<?php
/**
 * E-POSTBUSINESS API integration
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POSTBUSINESS
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Api\Metadata\Envelope;


/**
 * Class AbstractRecipient
 * @package EPost\Api\Metadata\Envelope
 */
abstract class AbstractRecipient implements \JsonSerializable
{

    /**
     * An array containing all field that can be configured
     *
     * @var array
     */
    protected static $configurableFields = [];


    /**
     * The data used for json
     *
     * @var array
     */
    protected $data = [];


    /**
     * Set a property in data array
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function __set($key, $value)
    {
        if (strlen($value)) {
            $this->data[$key] = $value;
        }

        return $this;
    }


    /**
     * Get a property from data array
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, static::getConfigurableFields())) {
            return $this->data[$key];
        }

        throw new \InvalidArgumentException(sprintf('Property "%s" is not supported', $key));
    }


    /**
     * Enable magic function call
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws \BadFunctionCallException
     */
    public function __call($name, $arguments)
    {
        if (0 === strncmp($name, 'set', 3)) {
            return $this->{lcfirst(substr($name, 3))} = reset($arguments);
        } elseif (0 === strncmp($name, 'get', 3)) {
            return $this->{lcfirst(substr($name, 3))};
        }

        throw new \BadFunctionCallException(sprintf('Unknown method "%s"', $name));
    }


    /**
     * Check if a value has been set
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }


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
     * Get all configurable fields
     *
     * @return array
     */
    public static function getConfigurableFields()
    {
        return static::$configurableFields;
    }


    /**
     *{@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->data;
    }
}
