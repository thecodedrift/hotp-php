<?php

declare(strict_types=1);

namespace jakobo\HOTP;

/**
 * HOTP Class
 * Based on the work of OAuth, and the sample implementation of HMAC OTP
 * http://tools.ietf.org/html/draft-mraihi-oath-hmac-otp-04#appendix-D
 * @author Jakob Heuser (firstname)@felocity.com
 * @copyright 2011-2020
 * @license BSD-3-Clause
 */
class HOTP
{
    /**
     * Generate a HOTP key based on a counter value (event based HOTP)
     * @param string $key the key to use for hashing
     * @param int $counter the number of attempts represented in this hashing
     * @param string $algorithm the HMAC hash algorithm to use, defaults to sha1
     * @return HOTPResult a HOTP Result which can be truncated or output
     */
    public static function generateByCounter(string $key, int $counter, string $algorithm = 'sha1'): HOTPResult
    {
        if (!in_array($algorithm, hash_hmac_algos(), true)) {
            throw new \InvalidArgumentException("Unsupported HMAC algorithm: {$algorithm}");
        }

        // The counter value can be more than one byte long,
        // so we need to pack it down properly.
        $curCounter = [ 0, 0, 0, 0, 0, 0, 0, 0 ];
        for ($i = 7; $i >= 0; $i--) {
            $curCounter[$i] = pack('C*', $counter);
            $counter = $counter >> 8;
        }

        // Pad to 8 chars
        $binCounter = str_pad(implode("", $curCounter), 8, chr(0), STR_PAD_LEFT);

        // HMAC
        $hash = hash_hmac($algorithm, $binCounter, $key);

        return new HOTPResult($hash);
    }

    /**
     * Generate a HOTP key based on a timestamp and window size
     * @param string $key the key to use for hashing
     * @param int $window the size of the window a key is valid for in seconds
     * @param int|false $timestamp a timestamp to calculate for, defaults to time()
     * @param string $algorithm the HMAC hash algorithm to use, defaults to sha1
     * @param int $startTime the Unix time to start counting time steps from (RFC 6238 "T0"), defaults to 0
     * @return HOTPResult a HOTP Result which can be truncated or output
     */
    public static function generateByTime(string $key, int $window, int|false $timestamp = false, string $algorithm = 'sha1', int $startTime = 0): HOTPResult
    {
        if ($window <= 0) {
            throw new \InvalidArgumentException('$window must be a positive integer');
        }

        if (!$timestamp && $timestamp !== 0) {
            // @codeCoverageIgnoreStart
            $timestamp = self::getTime();
            // @codeCoverageIgnoreEnd
        }

        if ($timestamp < 0) {
            throw new \InvalidArgumentException('$timestamp must not be negative');
        }

        if ($timestamp - $startTime < 0) {
            throw new \InvalidArgumentException('$timestamp must not be earlier than $startTime');
        }

        $counter = intval(($timestamp - $startTime) / $window) ;

        return self::generateByCounter($key, $counter, $algorithm);
    }

    /**
     * Generate a HOTP key collection based on a timestamp and window size
     * all keys that could exist between a start and end time will be included
     * in the returned array
     * @param string $key the key to use for hashing
     * @param int $window the size of the window a key is valid for in seconds
     * @param int $min the minimum window to accept before $timestamp
     * @param int $max the maximum window to accept after $timestamp
     * @param int|false $timestamp a timestamp to calculate for, defaults to time()
     * @param string $algorithm the HMAC hash algorithm to use, defaults to sha1
     * @param int $startTime the Unix time to start counting time steps from (RFC 6238 "T0"), defaults to 0
     * @return HOTPResult[]
     */
    public static function generateByTimeWindow(string $key, int $window, int $min = -1, int $max = 1, int|false $timestamp = false, string $algorithm = 'sha1', int $startTime = 0): array
    {
        if ($window <= 0) {
            throw new \InvalidArgumentException('$window must be a positive integer');
        }

        if (!$timestamp && $timestamp !== 0) {
            // @codeCoverageIgnoreStart
            $timestamp = self::getTime();
            // @codeCoverageIgnoreEnd
        }

        if ($timestamp < 0) {
            throw new \InvalidArgumentException('$timestamp must not be negative');
        }

        if ($timestamp - $startTime < 0) {
            throw new \InvalidArgumentException('$timestamp must not be earlier than $startTime');
        }

        $counter = intval(($timestamp - $startTime) / $window);
        $window = range($min, $max);

        $out = [];
        foreach ($window as $value) {
            $shiftCounter = $counter + $value;
            $out[$shiftCounter] = self::generateByCounter($key, $shiftCounter, $algorithm);
        }

        return $out;
    }

    /**
     * Gets the current time
     * Ensures we are operating in UTC for the entire framework
     * Restores the timezone on exit.
     * @return int the current time
     * @codeCoverageIgnore
     */
    public static function getTime(): int
    {
        // PHP's time is always UTC
        return time();
    }
}
