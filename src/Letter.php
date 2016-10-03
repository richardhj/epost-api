<?php
/**
 * E-POSTBUSINESS API integration
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POSTBUSINESS
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Api;

use EPost\Api\Exception\MissingPreconditionException;
use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;
use EPost\Api\Metadata\PostageInfo;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\MultipartStream;
use League\OAuth2\Client\Token\AccessToken;


/**
 * Class Letter
 * @package EPost\Api
 */
class Letter
{

    /**
     * Mailbox endpoint for production environment
     *
     * @var string
     */
    protected static $endpointMailboxProduction = 'https://mailbox.api.epost.de';


    /**
     * Mailbox endpoint for test and integration environment
     *
     * @var string
     */
    protected static $endpointMailboxTest = 'https://mailbox.api.epost-gka.de';


    /**
     * Send endpoint for production environment
     *
     * @var string
     */
    protected static $endpointSendProduction = 'https://send.api.epost.de';


    /**
     * Send endpoint for test and integration environment
     *
     * @var string
     */
    protected static $endpointSendTest = 'https://send.api.epost-gka.de';


    /**
     * A toggle to enable test and integration environment
     *
     * @var bool
     */
    protected $testEnvironment;


    /**
     * The Guzzle HTTP Client
     *
     * @var HttpClient
     */
    protected $httpClient;


    /**
     * The OAuth access token instance
     *
     * @var AccessToken
     */
    protected $accessToken;


    /**
     * The envelope (metadata)
     *
     * @var Envelope
     */
    protected $envelope;


    /**
     * The optional cover letter html formatted
     *
     * @var string
     */
    protected $coverLetter;


    /**
     * The attachments paths
     *
     * @var string[]
     */
    protected $attachments;


    /**
     * The delivery options
     *
     * @var DeliveryOptions
     */
    protected $deliveryOptions;


    /**
     * The postage info
     *
     * @var PostageInfo
     */
    protected $postageInfo;


    /**
     * The letter's id available after the draft was created
     *
     * @var string
     */
    protected $letterId;


    /**
     * Get the endpoint for mailbox api
     *
     * @return string
     */
    public function getEndpointMailbox()
    {
        return !$this->testEnvironment ? static::$endpointMailboxProduction : static::$endpointMailboxTest;
    }


    /**
     * Get the endpoint for send api
     *
     * @return string
     */
    public function getEndpointSend()
    {
        return !$this->testEnvironment ? static::$endpointSendProduction : static::$endpointSendTest;
    }


    /**
     * Set the access token
     *
     * @param AccessToken $accessToken
     *
     * @return self
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }


    /**
     * Get the access token
     *
     * @return AccessToken
     * @throws MissingPreconditionException
     */
    public function getAccessToken()
    {
        if (!$this->accessToken) {
            throw new MissingPreconditionException('An AccessToken instance must be passed');
        }

        return $this->accessToken;
    }


    /**
     * Set the envelope
     *
     * @param Envelope $envelope
     *
     * @return self
     */
    public function setEnvelope(Envelope $envelope)
    {
        $this->envelope = $envelope;

        return $this;
    }


    /**
     * Get the envelope
     *
     * @return Envelope
     * @throws MissingPreconditionException
     * @throws \LogicException
     */
    public function getEnvelope()
    {
        if (!$this->envelope) {
            throw new MissingPreconditionException('No Envelope provided! Provide one beforehand');
        }

        // Check for recipients
        if (empty($this->envelope->getRecipients())) {
            throw new \LogicException('No recipients provided! Add them beforehand');
        }

        return $this->envelope;
    }


    /**
     * Set the cover letter as html string
     *
     * @param string $coverLetter
     *
     * @return self
     */
    public function setCoverLetter($coverLetter)
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }


    /**
     * Get the html formatted cover letter
     *
     * @return string
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }


    /**
     * Add an attachment
     *
     * @param string $attachment The file path
     *
     * @return self
     */
    public function addAttachment($attachment)
    {
        if (!is_file($attachment)) {
            throw new \InvalidArgumentException('"%s" can not be found or is not a file');
        }

        $this->attachments[] = $attachment;

        return $this;
    }


    /**
     * Set attachments
     *
     * @param string[] $attachments The attachment paths
     *
     * @return self
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }


    /**
     * Get the attachments
     *
     * @return string[]
     * @throws MissingPreconditionException
     */
    public function getAttachments()
    {
        if (!count($this->attachments)) {
            throw new MissingPreconditionException('No attachments provided! Add at least one attachment');
        }

        return $this->attachments;
    }


    /**
     * Set the delivery options
     *
     * @param DeliveryOptions $deliveryOptions
     *
     * @return self
     */
    public function setDeliveryOptions(DeliveryOptions $deliveryOptions)
    {
        if ($this->envelope && $this->envelope->isNormalLetter()) {
            throw new \LogicException('Delivery options are not supported for non-printed letters.');
        }

        $this->deliveryOptions = $deliveryOptions;

        return $this;
    }


    /**
     * Get the delivery options
     *
     * @return DeliveryOptions|null
     */
    public function getDeliveryOptions()
    {
        return $this->deliveryOptions;
    }


    /**
     * Set the postage info
     *
     * @param PostageInfo $postageInfo
     *
     * @return self
     */
    public function setPostageInfo(PostageInfo $postageInfo)
    {
        $this->postageInfo = $postageInfo;

        return $this;
    }


    /**
     * Get the postage info
     *
     * @return PostageInfo
     * @throws MissingPreconditionException
     */
    public function getPostageInfo()
    {
        if (!$this->postageInfo) {
            throw new MissingPreconditionException('No postage info provided! Provide them beforehand');
        }

        // Set delivery options to postage info if they were passed to this instance
        if (!$this->postageInfo->getDeliveryOptions() && $this->getDeliveryOptions()) {
            $this->postageInfo->setDeliveryOptions($this->getDeliveryOptions());
        }

        // Set the attachment's file size for normal letters
        if (!$this->postageInfo->getLetterSize() && $this->postageInfo->isNormalLetter() && $this->attachments) {
            $size = array_reduce(
                $this->attachments,
                function ($carry, $path) {
                    $carry += filesize($path);

                    return $carry;
                },
                0
            );

            $this->postageInfo->setLetterSize(ceil($size / 1048576));
        }

        return $this->postageInfo;
    }


    /**
     * Set the letter id
     *
     * @param string $letterId
     *
     * @return self
     */
    public function setLetterId($letterId)
    {
        $this->letterId = $letterId;

        return $this;
    }


    /**
     * Get the letter id
     *
     * @return string
     * @throws MissingPreconditionException
     */
    public function getLetterId()
    {
        if (!$this->letterId) {
            throw new MissingPreconditionException('No letter id provided! Set letter id or create draft beforehand');
        }

        return $this->letterId;
    }


    /**
     * Enable/disable the test and integration environment
     *
     * @param boolean $testEnvironment
     *
     * @return self
     */
    public function setTestEnvironment($testEnvironment)
    {
        $this->testEnvironment = $testEnvironment;

        return $this;
    }


    /**
     * Create a draft by given envelope and attachments
     *
     * @return self
     * @throws BadResponseException See API Send Reference
     */
    public function create()
    {
        // Define multipart elements
        $multipartElements = [
            [
                'name'     => 'envelope',
                'contents' => \GuzzleHttp\json_encode($this->getEnvelope()),
                'headers'  => ['Content-Type' => $this->getEnvelope()->getMimeType()],
            ],
        ];

        // Add cover letter (if provided) to multipart elements
        if ($this->getCoverLetter()) {
            $multipartElements[] = [
                'name'     => 'cover_letter',
                'contents' => $this->getCoverLetter(),
                'headers'  => ['Content-Type' => 'text/html'],
            ];
        }

        // Add attachments to multipart elements
        foreach ($this->getAttachments() as $attachment) {
            $multipartElements[] = [
                'name'     => 'file',
                'contents' => fopen($attachment, 'rb'),
                'headers'  => ['Content-Type' => static::getMimeTypeOfFile($attachment)],
            ];
        }

        // Create multipart stream
        $multipart = new MultipartStream($multipartElements);

        // Define request options
        $requestOptions = [
            'headers' => [
                'Content-Type' => 'multipart/mixed; boundary='.$multipart->getBoundary(),
            ],
            'body'    => $multipart,
        ];

        $response = $this->getHttpClientForMailbox()->request('POST', '/letters', $requestOptions);
        $data = \GuzzleHttp\json_decode($response->getBody()->getContents());

        $this->setLetterId($data->letterId);

        return $this;
    }


    /**
     * Move the given letter to trash (idempotent call)
     *
     * @return self
     * @throws BadResponseException See API Send Reference
     */
    public function moveToTrash()
    {
        $this->getHttpClientForMailbox()->request('DELETE', '/letters/'.$this->getLetterId());

        return $this;
    }


    /**
     * Delete the given letter irrevocable
     *
     * @return self
     * @throws BadResponseException See API Send Reference
     */
    public function delete()
    {
        $this
            ->moveToTrash()
            ->getHttpClientForMailbox()->request('DELETE', '/trash/'.$this->getLetterId());

        return $this;
    }


    /**
     * Send the given letter. Delivery options should be set optionally for physical letters
     *
     * @return self
     * @throws BadResponseException See API Send Reference
     */
    public function send()
    {
        $options = [
            'headers' => [
                'Content-Source' => $this->getEndpointMailbox().'/letters/'.$this->getLetterId(),
            ],
        ];

        if ($this->getEnvelope()->isHybridLetter() && $this->getDeliveryOptions()) {
            $options['headers']['Content-Type'] = $this->getDeliveryOptions()->getMimeType();
            $options['json'] = $this->getDeliveryOptions();
        }

        $this->getHttpClientForSend()->request('POST', '/deliveries', $options);

        return $this;
    }


    /**
     * Query price information for a given letter (created beforehand) or for general purposes (with given postage info)
     *
     * @return \stdClass
     * @throws BadResponseException See API Send Reference
     * @throws MissingPreconditionException If neither letterId nor PostageInfo provided
     */
    public function queryPriceInformation()
    {
        // Try to fetch postage info for a particular draft that was created beforehand
        try {
            $options = [
                'headers' => [
                    'Content-Source' => $this->getEndpointMailbox().'/letters/'.$this->getLetterId(),
                ],
            ];

            if ($this->getEnvelope()->isHybridLetter() && $this->getDeliveryOptions()) {
                $options['headers']['Content-Type'] = $this->getDeliveryOptions()->getMimeType();
                $options['json'] = $this->getDeliveryOptions();
            }
        } // Fetch postage info without a given letter
        catch (MissingPreconditionException $e) {
            $options = [
                'headers' => [
                    'Content-Type' => $this->getPostageInfo()->getMimeType(),
                ],
                'json'    => $this->getPostageInfo(),
            ];
        }

        $response = $this->getHttpClientForSend()->request('POST', '/postage-info', $options);

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }


    /**
     * Get the http client with the mailbox api as endpoint
     *
     * @return HttpClient
     */
    protected function getHttpClientForMailbox()
    {
        return $this->getHttpClient($this->getEndpointMailbox());
    }


    /**
     * Get the http client with the send api as endpoint
     *
     * @return HttpClient
     */
    protected function getHttpClientForSend()
    {
        return $this->getHttpClient($this->getEndpointSend());
    }


    /**
     * Get a http client by given base uri and set the access token header
     *
     * @param string $baseUri
     *
     * @return HttpClient
     */
    private function getHttpClient($baseUri)
    {
        return new HttpClient(
            [
                'base_uri' => $baseUri,
                'headers'  => [
                    'x-epost-access-token' => $this->getAccessToken()->getToken(),
                ],
            ]
        );
    }


    /**
     * Get a file's mime type
     *
     * @param $path
     *
     * @return mixed
     */
    private static function getMimeTypeOfFile($path)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mime;
    }
}
