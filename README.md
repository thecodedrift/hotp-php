# HOTP - PHP Based HMAC One Time Passwords

**What is HOTP**:
HOTP is a class that simplifies One Time Password systems for PHP Authentication. The HOTP/TOTP Algorithms have been around for a bit, so this is a straightforward class to meet the test vector requirements.

**What works with HOTP/TOTP**:
It's been tested to the test vectors, and I've verified the time-sync hashes against the following:

- Android: Mobile-OTP
- iPhone: OATH Token

**Why would I use this**:
Who wouldn't love a simple drop-in class for HMAC Based One Time Passwords? It's a great extra layer of security (creating two-factor auth) and it's pretty darn zippy.

**Okay you sold me. Give me some docs**:

```php
use jakobo\HOTP\HOTP;

// event based
$result = HOTP::generateByCounter( $key, $counter );

// time based within a "window" of time
$result = HOTP::generateByTime( $key, $window );

// same as generateByTime, but for $min windows before and $max windows after
$result = HOTP::generateByTimeWindow( $key, $window, $min, $max );
```

with `$result`, you can do all sorts of neat things...

```php
$result->toString();

$result->toHex();

$result->toDec();

// how many digits in your OTP?
$result->toHotp( $length );
```

# Development

In order to support multiple PHP versions, a docker file and compose is available.

To build a php docker image with composer (and dependencies):

```bash
# Bring up the environment, mounts to /app in the container
docker compose up

# Get into the PHP environment
docker compose exec php bash

# Run composer
cd /app
composer install
composer test
```
