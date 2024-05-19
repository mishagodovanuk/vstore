<?php

declare(strict_types=1);

namespace Vstore\Router\View\Helper;

/**
 * Class Escaper
 */
class Escaper
{
    /**
     * @param string $string
     * @return string
     */
    public function escapeHtml(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * @param string $string
     * @return string
     */
    public function escapeUrl(string $string): string
    {
        return rawurlencode($string);
    }

    /**
     * @param string $string
     * @return bool|string
     * @throws \JsonException
     */
    public function escapeJs(string $string): bool|string
    {
        return json_encode(
            $string,
            JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );
    }

    /**
     * @param string $string
     * @param bool $strict
     * @return array|string|string[]|null
     */
    public function escapeHtmlAttr(string $string, bool $strict = true): array|string|null
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
     * @param string $string
     * @return bool
     */
    protected function checkEscape(string $string): bool
    {
        return $string === '' || ctype_digit($string);
    }
}
