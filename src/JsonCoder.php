<?php

namespace Sicoresq\JsonCoder;

final class JsonCoder
{
    public static function decode($payload): mixed
    {
        return self::decoder()->decode($payload);
    }

    public static function decodeAsArray($payload): ?array
    {
        return self::decoder()->decodeAsArray($payload);
    }

    public static function decodeAsObject($payload): object|array|null
    {
        return self::decoder()->decodeAsObject($payload);
    }

    public static function decoder(): JsonDecoder
    {
        return new JsonDecoder();
    }

    public static function encode($payload): string
    {
        return self::encoder()->encode($payload);
    }

    public static function encodeNullable($payload): ?string
    {
        return self::encoder()->encodeNullable($payload);
    }

    public static function encoder(): JsonEncoder
    {
        return new JsonEncoder();
    }
}
