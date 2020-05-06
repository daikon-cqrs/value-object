<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject\Fixture;

use Daikon\ValueObject\ValueObjectListInterface;
use Daikon\ValueObject\ValueObjectListTrait;

/**
 * @type Daikon\ValueObject\Date::fromNative
 */
final class DateList implements ValueObjectListInterface
{
    use ValueObjectListTrait;
}