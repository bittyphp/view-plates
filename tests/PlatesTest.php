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
    private $fixture = null;

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
     * @param mixed $paths
     * @param string $expected
     *
     * @dataProvider sampleInvalidPaths
     */
    public function testInvalidPaths($paths, string $expected): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Path must be a string or an array; '.$expected.' given.');

        new Plates($paths);
    }

    public function sampleInvalidPaths(): array
    {
        return [
            'object' => [(object) [], 'object'],
            'false' => [false, 'boolean'],
            'true' => [true, 'boolean'],
            'int' => [rand(), 'integer'],
        ];
    }

    /**
     * @param string $template
     * @param string $extension
     * @param mixed[] $data
     * @param string $expected
     *
     * @dataProvider sampleRender
     */
    public function testRender(string $template, string $extension, array $data, string $expected): void
    {
        $this->fixture = new Plates(__DIR__.'/templates/', ['extension' => $extension]);

        $actual = $this->fixture->render($template, $data);

        self::assertEquals($expected, $actual);
    }

    public function sampleRender(): array
    {
        $name = uniqid('name');

        return [
            'simple' => [
                'template' => 'test',
                'extension' => 'tpl',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.PHP_EOL.'Goodbye, '.$name,
            ],
            'nested' => [
                'template' => 'parent/test',
                'extension' => 'php',
                'data' => ['name' => $name],
                'expected' => 'Hello, '.$name.', from parent'.PHP_EOL,
            ],
            'multiple nested' => [
                'template' => 'parent/child/test',
                'extension' => 'php',
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
