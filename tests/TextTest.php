<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Text;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    private const FIXED_TEXT = 'hello world!';

    private Text $text;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_TEXT, $this->text->toNative());
        $this->assertEquals('', Text::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameText = Text::fromNative(self::FIXED_TEXT);
        $this->assertTrue($this->text->equals($sameText));
        $differentText = Text::fromNative('hello universe!');
        $this->assertFalse($this->text->equals($differentText));
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Text::fromNative('')->isEmpty());
        $this->assertTrue(Text::fromNative(null)->isEmpty());
        $this->assertFalse(Text::fromNative('0')->isEmpty());
        $this->assertFalse($this->text->isEmpty());
    }

    public function testToString(): void
    {
        $this->assertEquals(self::FIXED_TEXT, (string)$this->text);
    }

    public function testGetLength(): void
    {
        $this->assertEquals(12, $this->text->getLength());
        $detectOrder = mb_detect_order();
        mb_detect_order(['SJIS']);
        $intlText = Text::fromNative('ぁあぃいぅうぇえぉおかがきぎく');
        $this->assertEquals(23, $intlText->getLength());
        $this->assertEquals('ぁあぃいぅうぇえぉおかがきぎく', $intlText->toNative());
        mb_detect_order($detectOrder);
    }

    public function testMakeEmpty(): void
    {
        $this->assertEquals('', Text::makeEmpty()->toNative());
        $this->assertEquals('', (string)Text::makeEmpty());
        $this->assertTrue(Text::makeEmpty()->isEmpty());
        $this->assertEquals(0, Text::makeEmpty()->getLength());
    }

    protected function setUp(): void
    {
        $this->text = Text::fromNative(self::FIXED_TEXT);
    }
}
