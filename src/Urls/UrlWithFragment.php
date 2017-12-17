<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Urls;

class UrlWithFragment implements UrlInterface
{
    /**
     * The fragment to append to the url.
     *
     * @var string
     */
    private $fragment;

    /**
     * The delegate.
     *
     * @var \Ellipse\FastRoute\Urls\UrlInterface
     */
    private $delegate;

    /**
     * Set up an url with fragment with the given fragment and delegate.
     *
     * @param string                                $fragment
     * @param \Ellipse\FastRoute\Urls\UrlInterface  $delegate
     */
    public function __construct(string $fragment = '', UrlInterface $delegate)
    {
        $this->fragment = $fragment;
        $this->delegate = $delegate;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $url = (string) $this->delegate;

        return ($this->fragment == '') ? $url : $url . '#' . $this->fragment;
    }
}
