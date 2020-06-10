<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\DataStructure\TypedMapInterface;
use Daikon\Interop\MakeEmptyInterface;

interface ValueObjectMapInterface extends MakeEmptyInterface, TypedMapInterface, ValueObjectInterface
{
}
