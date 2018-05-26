<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class BlendHandlerTest extends TestCase {
    public $blendHander;
    protected $blendsDir = __DIR__ .'/TestBlends';

    public function setUp() {
        global $injector;
        parent::setUp();
        $this->blendHandler = $injector->make('BlendExchange\Handlers\BlendHandler');
    }
    public function testCaseValidatesGzipBlend () {
        $file = fopen($this->blendsDir . '/Valid_Compressed.blend','rb');
        $this->assertEmpty($this->blendHandler->validateBlend($file));
    }
    public function testCaseValidatesUncompressedBlend () {
        $file = fopen($this->blendsDir . '/Valid_No_Compression.blend','rb');
        $this->assertEmpty($this->blendHandler->validateBlend($file));
    }
    public function testCaseFailsInvalidGzipBlend () {
        $file = fopen($this->blendsDir . '/Invalid_Compressed.blend','rb');
        $this->assertEquals(1,count($this->blendHandler->validateBlend($file)));
    }
    public function testCaseFailsInvalidUncompressedBlend () {
        $file = fopen($this->blendsDir . '/Invalid_No_Compression.blend','rb');
        $this->assertEquals(1,count($this->blendHandler->validateBlend($file)));
    }
    public function testCaseFailsToBigBlend () {
        $file = fopen($this->blendsDir . '/To_Big_No_Compression.blend','rb');
        $this->assertEquals(1,count($this->blendHandler->validateBlend($file)));
    }
    public function testCaseValidatesRealUrls () {
        $urls = [
            'https://blender.stackexchange.com/questions/15045/how-do-i-align-the-viewport-to-a-face-normal#comment20857_15045', //TODO Integration test for comment URLs
            'https://blender.stackexchange.com/questions/15045/how-do-i-align-the-viewport-to-a-face-normal',
            'https://blender.stackexchange.com/questions/15045/',
            'https://blender.stackexchange.com/questions/15045',
            'https://blender.stackexchange.com/q/15045',
            'https://blender.stackexchange.com/q/15045/',
            'https://blender.stackexchange.com/q/15045/3127',
            'https://blender.stackexchange.com/q/15045/3127/',
        ];
        foreach ($urls as $url) {
            $this->assertEmpty($this->blendHandler->validateUrl($url));
        }
    }
    public function testCaseFailsFakeUrls () {
        $urls = [
            'https://blender.stackexchange.com/questions/42/answer-to-life-the-universe-and-everything',
            'https://blender.stackexchange.com/questions/42/',
            'https://blender.stackexchange.com/questions/42',
            'https://blender.stackexchange.com/q/42',
            'https://blender.stackexchange.com/q/42/',
            'https://blender.stackexchange.com/q/42/3127',
            'https://blender.stackexchange.com/questions/1234'
        ];
        foreach ($urls as $url) {
            $this->assertEquals(1,count($this->blendHandler->validateUrl($url)));
        }
    }
    public function testCaseFailsRealAnswerUrls () {
        $urls = [
            'https://blender.stackexchange.com/answer/42',
            'https://blender.stackexchange.com/a/1307/3127',
            'https://blender.stackexchange.com/a/1307',
            'https://blender.stackexchange.com/a/1307/',
        ];
        foreach ($urls as $url) {
            $this->assertEquals(1,count($this->blendHandler->validateUrl($url)));
        }
    }
    public function testCaseFailsRandomInputUrls () {
        $urls = [
            'Ẻνίŀ Ǘאָįċốďέ ǐŝ Έυîł',
            'Emoji. At least they aren\'t in a movie. 🐄🐄🐮🐮🐮',
            '\'\\""https:// // /  / ',
            'Gibberish',
            null
        ];
        foreach ($urls as $url) {
            $this->assertEquals(1,count($this->blendHandler->validateUrl($url)));
        }
    }
}