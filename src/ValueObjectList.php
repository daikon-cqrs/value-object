<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\DataStructure\TypedList;
use Daikon\Interop\MakeEmptyInterface;

class ValueObjectList extends TypedList implements MakeEmptyInterface, ValueObjectInterface
{
    use ValueObjectCollectionTrait;

    final public function __construct(iterable $objects = [])
    {
        $this->init($objects, static::inferValidTypes());
    }
}
