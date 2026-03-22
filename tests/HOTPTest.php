<?php

namespace jakobo\HOTP\Tests;

use jakobo\HOTP\HOTP;
use PHPUnit\Framework\TestCase;

/**
 * @copyright 2020
 * @license BSD-3-Clause
 * @version 1.0
 *
 * @covers \jakobo\HOTP\HOTP
 * @covers \jakobo\HOTP\HOTPResult
 */
class HOTPTest extends TestCase
{
    private const KEY = '12345678901234567890';

    public function provideHOTP(): array
    {
        return [
            [
                0, [
                    'HMAC'  => 'cc93cf18508d94934c64b65d8ba7667fb7cde4b0',
                    'hex'   => '4c93cf18',
                    'dec'   => '1284755224',
                    'hotp'  => '755224',
                ],
            ],
            [
                1, [
                    'HMAC'  => '75a48a19d4cbe100644e8ac1397eea747a2d33ab',
                    'hex'   => '41397eea',
                    'dec'   => '1094287082',
                    'hotp'  => '287082',
                ],
            ],
            [
                2, [
                    'HMAC'  => '0bacb7fa082fef30782211938bc1c5e70416ff44',
                    'hex'   => '82fef30',
                    'dec'   => '137359152',
                    'hotp'  => '359152',
                ],
            ],
            [
                3, [
                    'HMAC'  => '66c28227d03a2d5529262ff016a1e6ef76557ece',
                    'hex'   => '66ef7655',
                    'dec'   => '1726969429',
                    'hotp'  => '969429',
                ],
            ],
            [
                4, [
                    'HMAC'  => 'a904c900a64b35909874b33e61c5938a8e15ed1c',
                    'hex'   => '61c5938a',
                    'dec'   => '1640338314',
                    'hotp'  => '338314',
                ],
            ],
            [
                5, [
                    'HMAC'  => 'a37e783d7b7233c083d4f62926c7a25f238d0316',
                    'hex'   => '33c083d4',
                    'dec'   => '868254676',
                    'hotp'  => '254676',
                ],
            ],
            [
                6, [
                    'HMAC'  => 'bc9cd28561042c83f219324d3c607256c03272ae',
                    'hex'   => '7256c032',
                    'dec'   => '1918287922',
                    'hotp'  => '287922',
                ],
            ],
            [
                7, [
                    'HMAC'  => 'a4fb960c0bc06e1eabb804e5b397cdc4b45596fa',
                    'hex'   => '4e5b397',
                    'dec'   => '82162583',
                    'hotp'  => '162583',
                ],
            ],
            [
                8, [
                    'HMAC'  => '1b3c89f65e6c9e883012052823443f048b4332db',
                    'hex'   => '2823443f',
                    'dec'   => '673399871',
                    'hotp'  => '399871',
                ],
            ],
            [
                9, [
                    'HMAC'  => '1637409809a679dc698207310c8c7fc07290d9e5',
                    'hex'   => '2679dc69',
                    'dec'   => '645520489',
                    'hotp'  => '520489',
                ],
            ],
        ];
    }

    /** @dataProvider provideHOTP */
    public function testHOTP(int $seed, array $result): void
    {
        $hotp = HOTP::generateByCounter(self::KEY, $seed);

        $this->assertEquals(
            $result['HMAC'],
            $hotp->toString()
        );

        $this->assertEquals(
            $result['hex'],
            $hotp->toHex()
        );

        $this->assertEquals(
            $result['dec'],
            $hotp->toDec()
        );

        $this->assertEquals(
            $result['hotp'],
            $hotp->toHOTP(6)
        );
    }

    public function provideTOTP(): array
    {
        return [
            [ '59', '94287082' ],
            [ '1111111109', '07081804' ],
            [ '1111111111', '14050471' ],
            [ '1234567890', '89005924' ],
            [ '2000000000','69279037'],
        ];
    }

    /** @dataProvider provideTOTP */
    public function testTOTP(string $seed, string $result): void
    {
        $totp = HOTP::generateByTime(self::KEY, 30, $seed);

        $this->assertEquals(
            $result,
            $totp->toHOTP(8)
        );
    }

    public function provideGenerateByTimeWindow(): array
    {
        return [
                [ '1111111111', [
                    "404137",
                    "150727",
                    "731029",
                    "081804",
                    "050471",
                    "266759",
                    "306183",
                    "466594",
                    "754889",
                ],
            ],
        ];
    }

    /** @dataProvider provideGenerateByTimeWindow */
    public function testGenerateByTimeWindow(string $seed, array $result): void
    {
        $results = HOTP::generateByTimeWindow(
            self::KEY,
            30,
            -4,
            4,
            $seed
        );

        $actual = [];
        foreach ($results as $res) {
            $actual[] = $res->toHOTP(6);
        }

        $this->assertEquals(
            $result,
            $actual
        );
    }
}
