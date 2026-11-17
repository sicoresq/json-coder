# JSON Coder

A small, immutable wrapper around PHP's `json_encode()` and `json_decode()` with sensible defaults:

- errors throw `\JsonException` (`JSON_THROW_ON_ERROR`) by default,
- encoded output keeps Unicode characters and slashes unescaped,
- options are configured through fluent `with*()` methods that return a new instance.

## Installation

```bash
composer require sicoresq/json-coder
```

## Quick start

The `JsonCoder` facade exposes static shortcuts for the most common operations:

```php
use Sicoresq\JsonCoder\JsonCoder;

$json = JsonCoder::encode(['name' => 'Zażółć', 'url' => 'https://example.com']);
// {"name":"Zażółć","url":"https://example.com"}

$object = JsonCoder::decode($json);        // stdClass
$array  = JsonCoder::decodeAssoc($json);   // ['name' => 'Zażółć', 'url' => 'https://example.com']
```

## Encoding

```php
$encoder = JsonCoder::encoder(); // or: new JsonEncoder()

$encoder->encode(['a' => 1]); // {"a":1}
```

`encode()` returns `?string`. By default a failure throws `\JsonException`. With `withThrowError(false)` it returns `null` instead:

```php
JsonCoder::encoder()->withThrowError(false)->encode("\xB1\x31"); // null (invalid UTF-8)
```

### `encodeNullable()`

Returns `null` instead of an "empty" JSON document. This is handy when storing JSON in nullable database columns.

```php
$encoder->encodeNullable(null);     // null
$encoder->encodeNullable([]);       // null
$encoder->encodeNullable('   ');    // null
$encoder->encodeNullable(new \stdClass()); // null ("{}")
$encoder->encodeNullable(['a' => 1]);      // {"a":1}
```

The result is `null` if the input is `null`, an empty array, or a blank string. It is also `null` if the encoded output is `null`, `""`, `[]` or `{}`.

### Options

Each method returns a **new** encoder and leaves the original unchanged:

| Method                          | Flag                       | Default |
|---------------------------------|----------------------------|---------|
| `withThrowError(bool)`          | `JSON_THROW_ON_ERROR`      | on      |
| `withEscapeUnicode(bool)`       | `JSON_UNESCAPED_UNICODE`   | off (Unicode not escaped) |
| `withEscapeSlashes(bool)`       | `JSON_UNESCAPED_SLASHES`   | off (slashes not escaped) |
| `withPrettyPrint(bool)`         | `JSON_PRETTY_PRINT`        | off     |
| `withForceObject(bool)`         | `JSON_FORCE_OBJECT`        | off     |

```php
$json = JsonCoder::encoder()
    ->withPrettyPrint(true)
    ->withForceObject(true)
    ->encode([1, 2]);
// {
//     "0": 1,
//     "1": 2
// }
```

## Decoding

```php
$decoder = JsonCoder::decoder(); // or: new JsonDecoder()
```

| Method                | JSON objects become | Static shortcut              |
|-----------------------|---------------------|------------------------------|
| `decode()`            | `stdClass`          | `JsonCoder::decode()`        |
| `decodeAssoc()`       | associative array   | `JsonCoder::decodeAssoc()`   |

Deprecated:

- `decodeAsArray()`: use `decodeAssoc()` instead.
- `decodeAsObject()`: will be removed. Use `decode()`.

All decode methods:

- return `null` for a `null` or empty-string input (no exception is thrown),
- return `mixed`, because any valid JSON value can be decoded. A scalar document decodes to a scalar:

```php
$decoder->decodeAssoc('{"a":{"b":1}}'); // ['a' => ['b' => 1]]
$decoder->decode('{"a":{"b":1}}');      // stdClass { a: stdClass { b: 1 } }
$decoder->decode('[1, 2]');             // [1, 2] (JSON arrays stay PHP arrays)

$decoder->decodeAssoc('"abc"');  // 'abc'
$decoder->decodeAssoc('true');   // true
$decoder->decodeAssoc('0');      // 0
$decoder->decodeAssoc('3.14');   // 3.14
$decoder->decodeAssoc('null');   // null
$decoder->decodeAssoc('');       // null
```

### Options

| Method                 | Flag                  | Default |
|------------------------|-----------------------|---------|
| `withThrowError(bool)` | `JSON_THROW_ON_ERROR` | on      |

```php
JsonCoder::decode('{invalid');                                    // throws \JsonException
JsonCoder::decoder()->withThrowError(false)->decode('{invalid');  // null
```

## Testing

```bash
composer install
composer test
```

## License

MIT
