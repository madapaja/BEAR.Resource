<?php

declare(strict_types=1);
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
if (! function_exists('\\uri_template')) {
    /**
     * Implementation of URI Template(RFC6570) specification for PHP
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     *
     * @see http://pecl.php.net/package/uri_template
     */
    function uri_template($template, array $variables)
    {
        static $uriTemplate;

        if (! $uriTemplate) {
            $uriTemplate = new \Rize\UriTemplate();
        }

        return $uriTemplate->expand($template, $variables);
    }
}
