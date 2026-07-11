<?php

declare(strict_types=1);

namespace jakobo\HOTP;

/**
 * The HOTPResult Class converts an HOTP item to various forms
 * Supported formats include hex, decimal, string, and HOTP

 * @author Jakob Heuser (firstname)@felocity.com
 * @copyright 2011-2020
 * @license BSD-3-Clause
 */
class HOTPResult
{
    private ?int $decimal = null;
    private ?string $hex = null;

    public function __construct(private string $hash)
    {
    }

    /**
     * Returns the string version of the HOTP
     */
    public function toString(): string
    {
        return $this->hash;
    }

    /**
     * Returns the hex version of the HOTP
     */
    public function toHex(): string
    {
        if (!$this->hex) {
            $this->hex = dechex($this->toDec());
        }
        return $this->hex;
    }

    /**
     * Returns the decimal version of the HOTP
     */
    public function toDec(): int
    {
        if (!$this->decimal) {
            // store calculate decimal
            $hmacResult = [];

            // Convert to decimal
            foreach (str_split($this->hash, 2) as $hex) {
                $hmacResult[] = hexdec($hex);
            }

            // RFC 4226 dynamic truncation uses the low nibble of the digest's
            // final byte as the offset. SHA-1 digests are 20 bytes (index 19),
            // but wider digests (SHA-256/512) place that byte at a higher index.
            $offset = $hmacResult[array_key_last($hmacResult)] & 0xf;

            $this->decimal = (
                (($hmacResult[$offset + 0] & 0x7f) << 24) |
                (($hmacResult[$offset + 1] & 0xff) << 16) |
                (($hmacResult[$offset + 2] & 0xff) << 8) |
                ($hmacResult[$offset + 3] & 0xff)
            );
        }
        return $this->decimal;
    }

    /**
     * Returns the truncated decimal form of the HOTP
     * @param int $length the length of the HOTP to return
     * @return string
     */
    public function toHOTP(int $length): string
    {
        $str = str_pad((string)$this->toDec(), $length, "0", STR_PAD_LEFT);
        return substr($str, (-1 * $length));
    }
}
