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
        self::assertEquals(json_decode($payload, true), JsonCoder::decoder()->decodeAsArray($payload));

        self::assertEquals(null, JsonCoder::decoder()->decodeAsArray('null'));
        self::assertEquals(null, JsonCoder::decoder()->decodeAsArray(null));
        self::assertEquals(null, JsonCoder::decoder()->decodeAsArray(''));
    }

    public function testDecodeAsObject()
    {
        $payload = json_encode((object)[
            'a' => 1,
            'b' => ['c' => 'd']
        ]);
        self::assertEquals(json_decode($payload, false), JsonCoder::decoder()->decodeAsObject($payload));

        $payload = json_encode([[0, 1], ['a', 'b']]);
        self::assertEquals(json_decode($payload, false), JsonCoder::decoder()->decodeAsObject($payload));

        self::assertEquals(null, JsonCoder::decoder()->decodeAsObject('null'));
        self::assertEquals(null, JsonCoder::decoder()->decodeAsObject(null));
        self::assertEquals(null, JsonCoder::decoder()->decodeAsObject(''));
    }

    public function testDecode()
    {
        $payload = json_encode('abc');
        self::assertEquals(json_decode($payload, false), JsonCoder::decoder()->decode($payload));

        self::assertEquals(null, JsonCoder::decoder()->decode('null'));
        self::assertEquals(null, JsonCoder::decoder()->decode(null));
        self::assertEquals(null, JsonCoder::decoder()->decode(''));
    }
}
