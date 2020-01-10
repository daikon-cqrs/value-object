<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

/**
 * @type Daikon\ValueObject\ValueObjectInterface
 */
final class ValueObjectList implements ValueObjectListInterface
{
    use ValueObjectListTrait;
}
