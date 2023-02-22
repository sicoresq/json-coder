<?php

namespace Sicoresq\JsonCoder\Tests;

use PHPUnit\Framework\TestCase;
use Sicoresq\JsonCoder\JsonCoder;
use function json_decode;
use function json_encode;

class JsonDecoderTest extends TestCase
{
    public function testDecodeAsArray()
    {
        $payload = json_encode((object)[
            'a' => 1,
            'b' => ['c' => 'd']
        ]);
        self::assertEquals(JsonCoder::decoder()->decodeAsArray($payload), json_decode($payload, true));

        self::assertEquals(JsonCoder::decoder()->decodeAsArray('null'), null);
        self::assertEquals(JsonCoder::decoder()->decodeAsArray(null), null);
        self::assertEquals(JsonCoder::decoder()->decodeAsArray(''), null);
    }

    public function testDecodeAsObject()
    {
        $payload = json_encode((object)[
            'a' => 1,
            'b' => ['c' => 'd']
        ]);
        self::assertEquals(JsonCoder::decoder()->decodeAsObject($payload), json_decode($payload, false));

        $payload = json_encode([[0, 1], ['a', 'b']]);
        self::assertEquals(JsonCoder::decoder()->decodeAsObject($payload), json_decode($payload, false));

        self::assertEquals(JsonCoder::decoder()->decodeAsObject('null'), null);
        self::assertEquals(JsonCoder::decoder()->decodeAsObject(null), null);
        self::assertEquals(JsonCoder::decoder()->decodeAsObject(''), null);
    }

    public function testDecode()
    {
        $payload = json_encode('abc');
        self::assertEquals(JsonCoder::decoder()->decode($payload), json_decode($payload, false));

        self::assertEquals(JsonCoder::decoder()->decode('null'), null);
        self::assertEquals(JsonCoder::decoder()->decode(null), null);
        self::assertEquals(JsonCoder::decoder()->decode(''), null);
    }
}
