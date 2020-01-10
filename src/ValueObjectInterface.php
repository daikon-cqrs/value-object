<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface ValueObjectInterface extends FromNativeInterface, ToNativeInterface
{
    /** @psalm-suppress MissingParamType */
    public static function fromNative($state): self;

    /** @psalm-suppress MissingParamType */
    public function equals($comparator): bool;

    public function __toString(): string;
}
