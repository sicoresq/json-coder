<?php

namespace Sicoresq\JsonCoder\Tests;

use PHPUnit\Framework\TestCase;
use Sicoresq\JsonCoder\JsonCoder;
use Sicoresq\JsonCoder\JsonEncoder;
use stdClass;

use function json_encode;

use const JSON_FORCE_OBJECT;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

final class JsonEncoderTest extends TestCase
{
    /**
     * @dataProvider encoderDataProvider
     */
    public function testEncode(JsonEncoder $encoder, $payload, $expected)
    {
        self::assertEquals($expected, $encoder->encode($payload));
        self::assertEquals($expected, $encoder->encodeNullable($payload));
    }

    public function testEncodeNullable()
    {
        $encoder = new JsonEncoder();
        self::assertNull($encoder->encodeNullable(null));
        self::assertNull($encoder->encodeNullable(''));
        self::assertNull($encoder->encodeNullable(' '));
        self::assertNull($encoder->encodeNullable([]));
        self::assertEquals('null', $encoder->encode(null));
    }

    public function encoderDataProvider()
    {
        $obj = new stdClass();
        $obj->oa = '11';
        $obj->ob = 12;
        $arrayPayload = ['a' => 'ś', 'b' => '/', 'c' => $obj, 'd' => [], 'e' => new stdClass()];
        $objectPayload = new stdClass();
        $objectPayload->a = $obj;
        $objectPayload->b = [];

        return [
            [
                JsonCoder::encoder(),
                $arrayPayload,
                json_encode($arrayPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ],
            [
                JsonCoder::encoder()->withEscapeSlashes(true),
                $arrayPayload,
                json_encode($arrayPayload, JSON_UNESCAPED_UNICODE),
            ],
            [
                JsonCoder::encoder()->withEscapeSlashes(true)->withEscapeUnicode(true),
                $arrayPayload,
                json_encode($arrayPayload),
            ],
            [
                JsonCoder::encoder()->withEscapeSlashes(true)->withEscapeUnicode(true)->withPrettyPrint(true),
                $objectPayload,
                json_encode($objectPayload, JSON_PRETTY_PRINT),
            ],
            [
                JsonCoder::encoder()->withEscapeSlashes(true)->withEscapeUnicode(true)->withForceObject(true),
                $objectPayload,
                json_encode($objectPayload, JSON_FORCE_OBJECT),
            ],
        ];
    }
}
