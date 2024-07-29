<?php

declare(strict_types=1);

namespace Config;

/**
 * @immutable
 */
class DocTypes
{
    /**
     * List of valid document types.
     *
     * @var array<string, string>
     */
    public array $list = [
        // 'html5'             => '<!DOCTYPE html>',
    ];

    /**
     * Whether to remove the solidus (`/`) character for void HTML elements (e.g. `<input>`)
     * for HTML5 compatibility.
     *
     * Set to:
     *    `true` - to be HTML5 compatible
     *    `false` - to be XHTML compatible
     */
    public bool $html5 = true;
}
