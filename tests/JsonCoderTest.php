<?php

namespace Sicoresq\JsonCoder\Tests;

use PHPUnit\Framework\TestCase;
use Sicoresq\JsonCoder\JsonCoder;

final class JsonCoderTest extends TestCase
{
    public function testEncode()
    {
        $data = [
            'a' => 'b',
            'c' => [1, 2, 6, 'k' => ['345']]
        ];

        self::assertEquals(
            JsonCoder::encode($data),
            JsonCoder::encoder()->encode($data)
        );
    }

    public function testDecode()
    {
        $data = JsonCoder::encode([
            'a' => 'b',
            'c' => [1, 2, 6, 'k' => ['345']]
        ]);

        self::assertEquals(
            JsonCoder::decode($data),
            JsonCoder::decoder()->decode($data)
        );

        self::assertEquals(
            JsonCoder::decoder()->decodeAsArray($data),
            JsonCoder::decodeAsArray($data)
        );

        self::assertEquals(
            JsonCoder::decoder()->decodeAsObject($data),
            JsonCoder::decodeAsObject($data)
        );
    }
}
