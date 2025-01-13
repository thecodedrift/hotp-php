<?php

declare(strict_types=1);

namespace jakobo\HOTP;

/**
 * The HOTPResult Class converts an HOTP item to various forms
 * Supported formats include hex, decimal, string, and HOTP

 * @author Jakob Heuser (firstname)@felocity.com
 * @copyright 2011-2020
 * @license BSD-3-Clause
 * @version 1.0
 */
class HOTPResult
{
    protected $hash;
    protected $decimal;
    protected $hex;

    /**
     * Build an HOTP Result
     * @param string $value the value to construct with
     * @codeCoverageIgnore
     */
    public function __construct(string $value)
    {
        // store raw
        $this->hash = $value;
    }

    /**
     * Returns the string version of the HOTP
     * @return string
     */
    public function toString(): string
    {
        return $this->hash;
    }

    /**
     * Returns the hex version of the HOTP
     * @return string
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
     * @return int
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

            $offset = $hmacResult[19] & 0xf;

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
