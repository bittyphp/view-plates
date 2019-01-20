<?php

namespace Bitty\Tests\View;

use Bitty\View\AbstractView;
use Bitty\View\Plates;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;

class PlatesTest extends TestCase
{
    /**
     * @var Plates
     */
    protected $fixture = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new Plates(__DIR__.'/templates/');
    }

    public function testInstanceOf(): void
    {
        self::assertInstanceOf(AbstractView::class, $this->fixture);
    }

    /**
     * @param string $template
     * @param array $data
     * @param string $expected
     *
     * @dataProvider sampleRender
     */
    public function testRender(string $template, array $data, string $expected): void
    {
        $actual = $this->fixture->render($template, $data);

        self::assertEquals($expected, $actual);
    }

    public function sampleRender(): array
    {
        $name = uniqid('name');

        return [
            'simple' => [
                'template' => 'test',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.PHP_EOL.'Goodbye, '.$name,
            ],
            'nested' => [
                'template' => 'parent/test',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent'.PHP_EOL,
            ],
            'multiple nested' => [
                'template' => 'parent/child/test',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent/child'.PHP_EOL,
            ],
        ];
    }

    public function testRenderNamespacePath(): void
    {
        $name = uniqid('name');

        $this->fixture = new Plates(
            [
                'default' => __DIR__.'/templates/',
                'parent' => __DIR__.'/templates/parent/',
            ]
        );

        $actual = $this->fixture->render('parent::test', ['name' => $name]);

        self::assertEquals('Hello, '.$name.', from parent'.PHP_EOL, $actual);
    }

    public function testInvalidNamespaceThrowsException(): void
    {
        $path = __DIR__.'/templates/';

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Path "'.$path.'" does not have a namespace set.');

        $this->fixture = new Plates([$path]);
    }

    public function testGetEngine(): void
    {
        $actual = $this->fixture->getEngine();

        self::assertInstanceOf(Engine::class, $actual);
    }
}
