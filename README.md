# E-POSTBUSINESS API PHP integration

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]]()
[![Dependency Status][ico-dependencies]][link-dependencies]

This package provides an PHP integration of the E-POSTBUSINESS API.

## Install

Via Composer

``` bash
$ composer require richardhj/epost-api
```

**For new implementations, I recommend to start with `v0.10.x-dev`.**

## Usage

### Authenticate user

First of all you have to fetch an `AccessToken` instance by authenticating the user. You're probably going to use the [OAuth2 Provider](https://github.com/richardhj/oauth2-epost) for this.

```php
// Authenticate
/** @var League\OAuth2\Client\Token\AccessToken $token */
$token = $this->fetchAccessToken();
```

### Provide metadata
#### Envelope

We're going big steps forward and create a `Letter` instance. The `Letter` collects all metadata (envelope, delivery options…), creates a letter draft on the E-POST portal and finally sends the letter.

```php
// Create letter and envelope
$letter = new EPost\Api\Letter();
$envelope = new EPost\Api\Metadata\Envelope();
$envelope
    ->setSystemMessageTypeNormal()  // For sending an electronic letter *OR*
    ->setSystemMessageTypeHybrid()  // For sending a physical letter
    ->setSubject('Example letter');
```

##### Recipients
We created our envelope and we need to add the recipients. This is how for an electronic letter.

```php
// Add recipients for normal letter
$recipient = new EPost\Api\Metadata\Envelope\Recipient\Normal::createFromFriendlyEmail('John Doe <doe@example.com>');

$envelope->addRecipientNormal($recipient);
```

And this is how for a printed letter. For printed letters, only one recipient is valid!

```php
// Set recipients and delivery options for printed letter
$recipient = new EPost\Api\Metadata\Envelope\Recipient\Hybrid();
$recipient
    ->setFirstName('John')
    ->setLastName('Doe')
    ->setStreetName('…')
    ->setZipCode('1234')
    ->setCity('…');

$envelope->addRecipientPrinted($recipient);
```

#### Delivery options
We also define `DeliveryOptions` as they define whether the letter is going to be colored and so on. This is for printed letters only.

```php
// Set delivery options
$deliveryOptions = new EPost\Api\Metadata\DeliveryOptions();
$deliveryOptions
    ->setRegisteredStandard()   // This will make the letter sent as "Einschreiben ohne Optionen"
    ->setColorColored()         // To make it expensive
    ->setCoverLetterIncluded(); // The cover letter (with recipient address block) is included in the attachments

$letter->setDeliveryOptions($deliveryOptions);
```

### Finishing

We're going to start the communication with the E-POST portal.

```php
// Prepare letter
$letter
    ->setTestEnvironment(true)
    ->setAccessToken($token)
    ->setEnvelope($envelope)
    ->setCoverLetter('This is an example');

// Set attachments
$letter->addAttachment('/var/www/test.pdf');

// Create and send letter
try {
    $letter
        ->create()
        ->send();

} catch (GuzzleHttp\Exception\ClientException $e) {
    $errorInformation = \GuzzleHttp\json_decode($e->getResponse()->getBody());
}
```

### Fetch postage info

If you wonder how expensive the letter is going to be.

Case 1: You already defined a letter with envelope and so on:

```php
$priceInformation = $letter->queryPriceInformation();

var_dump($priceInformation);
```

Case 2: You need to provide `PostageInfo`:

```php
$postageInfo = new EPost\Api\Metadata\PostageInfo();
$postageInfo
    ->setLetterTypeHybrid()
    ->setLetterSize(3)
    ->setDeliveryOptions($deliveryOptions);
    
$letter = new EPost\Api\Letter();
$priceInformation = $letter->queryPriceInformation();

var_dump($priceInformation);
```

### Delete letters

If you already have a `Letter` instance, deleting is that easy:

```php
$letter
    ->create() // Yeah, it must be created beforehand, so we have a "letterId"
    ->delete();
```

Otherwise you need to know the `letterId`.

```php
$letter = new EPost\Api\Letter();
$letter
    ->setLetterId('asdf-124-asdf')
    ->delete();
```

`delete()` will delete the letter irrecoverably on the E-POST portal. You have to possibility to use `moveToTrash()` otherwise. 

## License

The  GNU Lesser General Public License (LGPL).

## Contributing

Please follow the [Symfony Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html).

## Beispiel-Konzept

[Dieses Konzept][link-concept] erklärt die verschiedenen Komponenten, die im Rahmen einer E-POSTBUSINESS-Integration für das CMS Contao genutzt wurden.

[![Konzept][image-concept]][link-concept]

[ico-version]: https://img.shields.io/packagist/v/richardhj/epost-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-LGPL-brightgreen.svg?style=flat-square
[ico-dependencies]: https://www.versioneye.com/php/richardhj:epost-api/badge.svg?style=flat-square

[image-concept]: https://www.dropbox.com/s/rfouu1bidkg62zs/Konzept_Henkenjohann_E-POST-Contao-1.png?dl=1

[link-packagist]: https://packagist.org/packages/richardhj/epost-api
[link-dependencies]: https://www.versioneye.com/php/richardhj:epost-api
[link-concept]: https://www.dropbox.com/s/fd7hl33galgy8jh/Konzept_Henkenjohann_E-POST-Contao.pdf?dl=0
