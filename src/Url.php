<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use Ellipse\FastRoute\Urls\UrlInterface;
use Ellipse\FastRoute\Urls\UrlWithFragment;
use Ellipse\FastRoute\Urls\UrlWithQueryString;
use Ellipse\FastRoute\Urls\UrlWithPath;

class Url implements UrlInterface
{
    /**
     * The delegate.
     *
     * @var \Ellipse\FastRoute\Urls\UrlInterface
     */
    private $delegate;

    /**
     * Set up an url with the given path, query parameters and fragment.
     *
     * @param \Ellipse\FastRoute\Path   $path
     * @param array                     $query
     * @param string                    $fragment
     */
    public function __construct(Path $path, array $query = [], string $fragment = '')
    {
        $this->delegate = new UrlWithFragment(
            $fragment,
            new UrlWithQueryString(
                $query,
                new UrlWithPath($path)
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return (string) $this->delegate;
    }
}
