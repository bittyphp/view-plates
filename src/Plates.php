<?php

namespace Bitty\View;

use Bitty\View\AbstractView;
use League\Plates\Engine;

/**
 * This acts as a very basic wrapper to implement the Plates templating engine.
 *
 * If more detailed customization is needed, you can access the Plates engine
 * directly using getEngine().
 *
 * @see http://platesphp.com/
 */
class Plates extends AbstractView
{
    /**
     * @var Engine
     */
    protected $engine = null;

    /**
     * @param string[]|string $paths
     * @param mixed[] $options
     */
    public function __construct($paths, array $options = [])
    {
        $path = is_string($paths) ? $paths : reset($paths);

        $this->engine = new Engine($path ?: null, $options['extension'] ?? 'php');

        if (is_array($paths)) {
            foreach ($paths as $name => $path) {
                if (is_numeric($name)) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Path "%s" does not have a namespace set.',
                            $path
                        )
                    );
                }
                $this->engine->addFolder($name, $path);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function render(string $template, $data = []): string
    {
        return $this->engine->render($template, $data);
    }

    /**
     * Gets the Plates engine.
     *
     * This allows for direct manipulation of anything not already defined here.
     *
     * @return Engine
     */
    public function getEngine(): Engine
    {
        return $this->engine;
    }
}