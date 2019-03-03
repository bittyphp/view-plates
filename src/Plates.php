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
    private $engine = null;

    /**
     * @param string[]|string|null $paths
     * @param mixed[] $options
     */
    public function __construct($paths, array $options = [])
    {
        $path = $this->getPrimaryPath($paths);

        $this->engine = new Engine($path, $options['extension'] ?? 'php');

        if (!is_array($paths)) {
            return;
        }

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

    /**
     * Gets the primary path templates are in.
     *
     * @param string[]|string|null $paths
     *
     * @return string|null
     */
    private function getPrimaryPath($paths): ?string
    {
        if (is_array($paths)) {
            return reset($paths) ?: null;
        }

        if (is_string($paths)) {
            return $paths;
        }

        if (is_null($paths)) {
            return null;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Path must be a string or an array; %s given.',
                gettype($paths)
            )
        );
    }
}
