<?php

namespace Sicoresq\JsonCoder;

use function json_decode;

use const JSON_FORCE_OBJECT;
use const JSON_OBJECT_AS_ARRAY;
use const JSON_THROW_ON_ERROR;

final class JsonDecoder
{
    private int $options = JSON_THROW_ON_ERROR;

    public function decode(?string $jsonString): mixed
    {
        if ($jsonString === null || $jsonString === '') {
            return null;
        }
        return json_decode($jsonString, false, 512, $this->options);
    }

    public function decodeAssoc(?string $jsonString): mixed
    {
        if ($jsonString === null || $jsonString === '') {
            return null;
        }
        return json_decode($jsonString, associative: true, flags: $this->options);
    }

    /**
     * @deprecated use decodeAssoc() instead
     */
    public function decodeAsArray(?string $jsonString): mixed
    {
        if ($jsonString === null || $jsonString === '') {
            return null;
        }
        return json_decode($jsonString, true, 512, $this->options | JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @deprecated will be removed
     */
    public function decodeAsObject(?string $jsonString): mixed
    {
        if ($jsonString === null || $jsonString === '') {
            return null;
        }
        return json_decode($jsonString, false, 512, $this->options | JSON_FORCE_OBJECT);
    }

    public function withThrowError(bool $throwError = true): self
    {
        return $this->cloneObjectWithOption($throwError, JSON_THROW_ON_ERROR);
    }

    private function cloneObjectWithOption(bool $set, int $option): self
    {
        $new = clone $this;
        $new->options = $set ? $this->options | $option : $this->options & ~$option;
        return $new;
    }

}
