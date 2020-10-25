<?php

namespace Sicoresq\JsonCoder;

use function json_decode;
use const JSON_FORCE_OBJECT;
use const JSON_OBJECT_AS_ARRAY;
use const JSON_THROW_ON_ERROR;

final class JsonDecoder
{
    private int $defaultOptions = JSON_THROW_ON_ERROR;

    public function decode(?string $jsonString)
    {
        if (empty($jsonString)) {
            return null;
        }
        return json_decode($jsonString, false, 512, $this->defaultOptions);
    }

    public function decodeAsArray(?string $jsonString): ?array
    {
        if (empty($jsonString)) {
            return null;
        }
        return json_decode($jsonString, true, 512, $this->defaultOptions | JSON_OBJECT_AS_ARRAY);
    }

    public function decodeAsObject(?string $jsonString): ?object
    {
        if (empty($jsonString)) {
            return null;
        }
        return json_decode($jsonString, false, 512, $this->defaultOptions | JSON_FORCE_OBJECT);
    }

    public function withThrowError(bool $throwError = true): self
    {
        return $this->cloneObjectWithOption($throwError, JSON_THROW_ON_ERROR);
    }

    private function cloneObjectWithOption(bool $set, int $option): self
    {
        $new = clone $this;
        $new->defaultOptions = $set ? $this->defaultOptions | $option : $this->defaultOptions & ~$option;
        return $new;
    }

}
