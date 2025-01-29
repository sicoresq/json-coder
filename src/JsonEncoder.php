<?php

namespace Sicoresq\JsonCoder;

use function json_encode;

use const JSON_FORCE_OBJECT;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

final class JsonEncoder
{
    private int $options = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    public function encode($item): string
    {
        return json_encode($item, $this->options);
    }

    public function encodeNullable($item): ?string
    {
        if (
            $item === null
            || (is_array($item) && !$item)
            || (is_string($item) && !trim($item))
        ) {
            return null;
        }
        $encoded = json_encode($item, $this->options);

        if (in_array(trim($encoded), ['null', '', '""', '[]', '{}'], true)) {
            return null;
        }

        return $encoded;
    }

    public function withThrowError(bool $throwError): self
    {
        return $this->cloneObjectWithOption($throwError, JSON_THROW_ON_ERROR);
    }

    public function withForceObject(bool $forceObject): self
    {
        return $this->cloneObjectWithOption($forceObject, JSON_FORCE_OBJECT);
    }

    public function withPrettyPrint(bool $pretty): self
    {
        return $this->cloneObjectWithOption($pretty, JSON_PRETTY_PRINT);
    }

    public function withEscapeSlashes(bool $escape): self
    {
        return $this->cloneObjectWithOption(!$escape, JSON_UNESCAPED_SLASHES);
    }

    public function withEscapeUnicode(bool $escape): self
    {
        return $this->cloneObjectWithOption(!$escape, JSON_UNESCAPED_UNICODE);
    }

    private function cloneObjectWithOption(bool $set, int $option): self
    {
        $new = clone $this;
        $new->options = $set ? $this->options | $option : $this->options & ~$option;
        return $new;
    }
}
