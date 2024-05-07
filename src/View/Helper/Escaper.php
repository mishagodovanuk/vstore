<?php

namespace Vstore\Router\View\Helper;

/**
 *
 */
class Escaper
{
    /**
     * @param $string
     * @return string
     */
    public function escapeHtml($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * @param $string
     * @return string
     */
    public function escapeUrl($string): string
    {
        return rawurlencode($string);
    }

    /**
     * @param $string
     * @return bool|string
     * @throws \JsonException
     */
    public function escapeJs($string): bool|string
    {
        return json_encode($string, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }

    /**
     * @param $string
     * @param $strict
     * @return array|mixed|string|string[]|null
     */
    public function escapeHtmlAttr($string, $strict = true)
    {
        if($this->checkEscape($string)) {
            return $string;
        }

        if($strict === true) {
            return preg_replace_callback('/[^a-z0-9,\.\-_]/iSu', 'static::escapeAttrChar', $string);
        }
        return $this->escapeHtml($string);
    }

    /**
     * @param $string
     * @return bool
     */
    protected function checkEscape($string): bool
    {
        return $string === '' || ctype_digit($string);
    }
}