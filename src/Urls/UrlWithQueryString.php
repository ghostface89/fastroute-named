<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Urls;

class UrlWithQueryString implements UrlInterface
{
    /**
     * The query parameters to append to the url.
     *
     * @var array
     */
    private $query;

    /**
     * The delegate.
     *
     * @var \Ellipse\FastRoute\Urls\UrlInterface
     */
    private $delegate;

    /**
     * Set up an url with query string with the given query parameters and
     * delegate.
     *
     * @param array                                 $query
     * @param \Ellipse\FastRoute\Urls\UrlInterface  $delegate
     */
    public function __construct(array $query = [], UrlInterface $delegate)
    {
        $this->query = $query;
        $this->delegate = $delegate;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $url = (string) $this->delegate;

        return (count($this->query) > 0)
            ? $url . '?' . http_build_query($this->query)
            : $url;
    }
}
