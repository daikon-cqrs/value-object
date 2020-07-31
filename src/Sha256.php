<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;
use Daikon\Interop\MakeEmptyInterface;
use Daikon\ValueObject\ValueObjectInterface;

final class Sha256 implements MakeEmptyInterface, ValueObjectInterface
{
    private ?string $hash;

    public static function generate(): self
    {
        return new self(hash(
            'sha256',
            sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            )
        ));
    }

    /** @param null|string $state */
    public static function fromNative($state): self
    {
        return new self($state);
    }

    public static function makeEmpty(): self
    {
        return new self;
    }

    public function toNative()
    {
        return $this->hash;
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function isEmpty(): bool
    {
        return empty($this->hash);
    }

    public function __toString(): string
    {
        return (string)$this->hash;
    }

    private function __construct(?string $hash = null)
    {
        Assertion::nullOrRegex($hash, '/^[a-f0-9]{64}$/', 'Invalid format.');
        $this->hash = $hash;
    }
}
