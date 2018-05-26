<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use BlendExchange\Input\QuestionLink;

final class QuestionLinkValidatorTest extends TestCase {
    public $questionLinkValidator;
    protected $blendsDir = __DIR__ .'/TestBlends';

    public function setUp() {
        global $injector;
        parent::setUp();
        $this->questionLinkValidator = $injector->make('BlendExchange\Input\QuestionLinkValidator');
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
            $ql = new QuestionLink($url);
            $this->assertEquals($this->questionLinkValidator->validate($ql),true);
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
            $ql = new QuestionLink($url);
            $this->assertEquals($this->questionLinkValidator->validate($ql),false);
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
            $ql = new QuestionLink($url);
            $this->assertEquals($this->questionLinkValidator->validate($ql),false);
        }
    }
    public function testCaseFailsRandomInputUrls () {
        $urls = [
            'Ẻνίŀ Ǘאָįċốďέ ǐŝ Έυîł',
            'Emoji. At least they aren\'t in a movie. 🐄🐄🐮🐮🐮',
            '\'\\""https:// // /  / ',
            'Gibberish'
        ];
        foreach ($urls as $url) {
            $ql = new QuestionLink($url);
            $this->assertEquals($this->questionLinkValidator->validate($ql),false);
        }
    }
}