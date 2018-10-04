<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
*/

namespace Gibbon\View;

use PHPUnit\Framework\TestCase;
use Gibbon\Domain\System\Theme;
use Gibbon\Domain\System\Module;

/**
 * @covers Page
 */
class PageTest extends TestCase
{
    protected $mockModule;
    protected $mockTheme;

    public function setUp()
    {
        $this->mockModule = $this->createMock(Module::class);
        $this->mockModule->method('stylesheets')->willReturn(new AssetBundle());
        $this->mockModule->method('scripts')->willReturn(new AssetBundle());

        $this->mockTheme = $this->createMock(Theme::class);
        $this->mockTheme->method('stylesheets')->willReturn(new AssetBundle());
        $this->mockTheme->method('scripts')->willReturn(new AssetBundle());
    }

    public function testCanConstructFromParams()
    {
        $params = ['title' => 'Foo Bar', 'address' => 'fiz/buzz'];
        $page = new Page($params);

        $this->assertEquals('Foo Bar', $page->getTitle());
        $this->assertEquals('fiz/buzz', $page->getAddress());
    }

    public function testAddsErrors()
    {
        $page = new Page();
        $page->addError('This is an error!');
     
        $this->assertContains('This is an error!', $page->getAlerts('error'));
    }

    public function testAddsWarnings()
    {
        $page = new Page();
        $page->addWarning('This is a warning?');
     
        $this->assertContains('This is a warning?', $page->getAlerts('warning'));
    }

    public function testAddsMessages()
    {
        $page = new Page();
        $page->addMessage('This is (maybe) a message.');
     
        $this->assertContains('This is (maybe) a message.', $page->getAlerts('message'));
    }

    public function testGetsAlerts()
    {
        $page = new Page();
        $page->addError('This is an error!');
        $page->addWarning('This is a warning?');
        $page->addMessage('This is (maybe) a message.');
     
        $this->assertCount(3, $page->getAlerts());
    }

    public function testAddsHeadExtra()
    {
        $page = new Page();
        $page->addHeadExtra('<style></style>');
     
        $this->assertContains('<style></style>', $page->getExtraCode('head'));
    }

    public function testAddsFootExtra()
    {
        $page = new Page();
        $page->addFootExtra('<script></script>');
     
        $this->assertContains('<script></script>', $page->getExtraCode('foot'));
    }

    public function testAddsSidebarExtra()
    {
        $page = new Page();
        $page->addSidebarExtra('<div></div>');
     
        $this->assertContains('<div></div>', $page->getExtraCode('sidebar'));
    }

    public function testCanAddStylesheets()
    {
        $page = new Page();
        $page->stylesheets()->add('foo', 'bar/baz');

        $this->assertArrayHasKey('foo',  $page->getAllStylesheets());
    }

    public function testCanGetAllStylesheets()
    {
        $page = new Page(['module' => $this->mockModule, 'theme' => $this->mockTheme]);

        $page->stylesheets()->add('foo', 'bar/baz');
        $page->getModule()->stylesheets()->add('fiz', 'bar/baz');
        $page->getTheme()->stylesheets()->add('buz', 'bar/baz');

        $this->assertEquals(['foo', 'fiz', 'buz'], array_keys($page->getAllStylesheets()));
    }

    public function testCanAddScripts()
    {
        $page = new Page();
        $page->scripts()->add('fiz', 'bar/baz', ['context' => 'head']);

        $this->assertArrayHasKey('fiz',  $page->getAllScripts('head'));
    }

    public function testCanGetAllScripts()
    {
        $page = new Page(['module' => $this->mockModule, 'theme' => $this->mockTheme]);

        $page->scripts()->add('foo', 'bar/baz', ['context' => 'head']);
        $page->getModule()->scripts()->add('fiz', 'bar/baz', ['context' => 'foot']);
        $page->getTheme()->scripts()->add('buz', 'bar/baz', ['context' => 'head']);

        $this->assertEquals(['foo', 'buz'], array_keys($page->getAllScripts('head')));
    }
}