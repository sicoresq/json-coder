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

    public function testDecodeAssoc()
    {
        $payload = json_encode((object)[
            'a' => 1,
            'b' => (object)['c' => 'd'],
            'e' => [1, 2],
        ]);
        self::assertSame(['a' => 1, 'b' => ['c' => 'd'], 'e' => [1, 2]], JsonCoder::decoder()->decodeAssoc($payload));

        self::assertNull(JsonCoder::decoder()->decodeAssoc('null'));
        self::assertNull(JsonCoder::decoder()->decodeAssoc(null));
        self::assertNull(JsonCoder::decoder()->decodeAssoc(''));
    }

    public function testDecodeThrowsOnInvalidJson()
    {
        $this->expectException(\JsonException::class);
        JsonCoder::decoder()->decodeAssoc('{invalid');
    }

    public function testDecodeReturnsNullOnInvalidJsonWithoutThrowError()
    {
        self::assertNull(JsonCoder::decoder()->withThrowError(false)->decodeAssoc('{invalid'));
    }

    public static function scalarProvider(): array
    {
        return [
            'string' => ['"abc"', 'abc'],
            'empty string' => ['""', ''],
            'true' => ['true', true],
            'false' => ['false', false],
            'zero' => ['0', 0],
            'string zero' => ['"0"', '0'],
            'integer' => ['42', 42],
            'negative integer' => ['-7', -7],
            'float' => ['3.14', 3.14],
        ];
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testDecodeReturnsScalar(string $payload, mixed $expected)
    {
        self::assertSame($expected, JsonCoder::decoder()->decode($payload));
        self::assertSame($expected, JsonCoder::decode($payload));
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testDecodeAssocReturnsScalar(string $payload, mixed $expected)
    {
        self::assertSame($expected, JsonCoder::decoder()->decodeAssoc($payload));
        self::assertSame($expected, JsonCoder::decodeAssoc($payload));
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testDecodeAsArrayReturnsScalar(string $payload, mixed $expected)
    {
        self::assertSame($expected, JsonCoder::decoder()->decodeAsArray($payload));
        self::assertSame($expected, JsonCoder::decodeAsArray($payload));
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testDecodeAsObjectReturnsScalar(string $payload, mixed $expected)
    {
        self::assertSame($expected, JsonCoder::decoder()->decodeAsObject($payload));
        self::assertSame($expected, JsonCoder::decodeAsObject($payload));
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
