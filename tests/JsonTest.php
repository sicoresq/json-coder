<?php

namespace Sicoresq\JsonCoder\Tests;

use PHPUnit\Framework\TestCase;
use Sicoresq\JsonCoder\JsonCoder;

final class JsonTest extends TestCase
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
}
