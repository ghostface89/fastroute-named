<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Urls;

use Ellipse\FastRoute\Path;

class UrlWithPath implements UrlInterface
{
    /**
     * The path.
     *
     * @var string
     */
    private $path;

    /**
     * Set up an url with path with the given path. Unfortunately ->value()
     * can't be called in ->__toString() because it may throw exceptions.
     *
     * @param \Ellipse\FastRoute\Path $path
     */
     public function __construct(Path $path)
     {
         $this->path = $path->value();
     }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->path;
    }
}
