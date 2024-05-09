<?php

namespace Vstore\Router\View;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Vstore\Router\View\Helper\Escaper;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Layout processor class.
 */
class LayoutProccessor
{
    /**
     * @var string|mixed
     */
    protected string $layout;

    /**
     * @var string
     */
    protected string $template;

    /**
     * @var string
     */
    protected string $title = 'Site title';

    /**
     * @var Escaper
     */
    protected Escaper $escaper;

    /**
     * @var FlashBagInterface
     */
    protected FlashBagInterface $messages;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param Escaper $escaper
     * @param Session $session
     * @param string $layout
     */
    public function __construct(
        Escaper $escaper,
        Session $session,
        string $layout = 'main'
    ) {
        $this->layout = $layout;
        $this->messages = $session->getFlashBag();
    }

    /**
     * @param string $template
     * @param array $data
     * @param string|null $layout
     * @return false|string
     */
    public function render(
        string $template,
        array $data = [],
        string|null $layout = null
    ): bool|string {
        if ($layout !== null) {
            $this->layout = $layout;
        }

        if (!empty($data)) {
            $this->setData($data);
        }

        $this->template = $template;
        ob_start();
        require_once "view/layouts/{$this->layout}.phtml";

        return ob_get_clean();
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param array|string $dataKey
     * @param $value
     * @return $this
     */
    public function setData(array|string $dataKey, $value=null): static
    {
        if (is_array($dataKey) && !empty($dataKey)) {
            $this->data = array_merge($this->data, $dataKey);

            return $this;
        }

        if (is_string($dataKey) && $value !== null) {
            $this->data[$dataKey] = $value;

            return $this;
        }

       return $this;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getData(string|null $key = null): mixed
    {
        if (is_string($key) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $this->data;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasData(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return Escaper
     */
    protected function getEscaper(): Escaper
    {
        return $this->escaper;
    }

    /**
     * @return void
     */
    protected function displayBody(): void
    {
        include_once "view/templates/{$this->template}.phtml";
    }

    /**
     * @return FlashBagInterface
     */
    public function getMessages() : FlashBagInterface
    {
        return $this->messages;
    }
}
