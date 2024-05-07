<?php

namespace Vstore\Router\View;

use Vstore\Router\View\Helper\Escaper;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
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
     * @var array
     */
    protected $messages;

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
        $this->messages = $session->getFlashBag()->all();
    }

    /**
     * @param string $template
     * @param array $data
     * @param null $layout
     * @return false|string
     */
    public function render(string $template, array $data = [], string $layout = null)
    {
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
     * @return Escaper
     */
    protected function getEscaper(): Escaper
    {
        return $this->escaper;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle(string $title)
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
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return void
     */
    protected function displayBody(): void
    {
        include_once "view/templates/{$this->template}.phtml";
    }

    /**
     * @param array|string $dataKey
     * @param $value
     * @return $this
     */
    public function setData(array | string $dataKey, $value=null)
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
     * @param $key
     * @return array|mixed
     */
    public function getData($key = null)
    {
        if (is_string($key) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $this->data;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasData($key): bool
    {
        return array_key_exists($key, $this->data);
    }
}
