<?php

namespace Sicoresq\JsonCoder;

final class JsonCoder
{
    public static function decoder(): JsonDecoder
    {
        return new JsonDecoder();
    }

    public static function encode($payload): string
    {
        return self::encoder()->encode($payload);
    }

    public static function encoder(): JsonEncoder
    {
        return new JsonEncoder();
    }

}
