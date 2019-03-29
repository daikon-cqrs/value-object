<?php
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\ValueObject;

use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface ValueObjectInterface extends FromNativeInterface, ToNativeInterface
{
    public function equals($value): bool;

    public function __toString(): string;
}
