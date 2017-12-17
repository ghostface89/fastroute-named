<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Urls;

interface UrlInterface
{
    /**
     * Return a string representation of the url.
     *
     * @return string
     */
    public function __toString();
}
