<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Yaml;

/**
 * Contains constants that identify parts of the document
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class YamlPart
{
    /**
     * Start of the document, three hypens
     * @since 1.0-sofia
     */
    const DOC_START = '---';

    /**
     * End of the document, three periods
     * @since 1.0-sofia
     */
    const DOC_END = '...';

    /**
     * Key value separator
     * @since 1.0-sofia
     */
    const KEY_VALUE_SEPARATOR = ':';

    /**
     * Sequence Item Bullet
     * @since 1.0-sofia
     */
    const SEQUENCE_ITEM_BULLET = '- ';

    /**
     * Sequence Item Bullet on an Empty Line
     * @since 1.0-sofia
     */
    const SEQUENCE_ITEM_BULLET_EMPTYLINE = "-\n";

    /**
     * Quotation markers
     * @return array Returns an array of quote markers
     * @since 1.0-sofia
     */
    public static function quotationMarkers()
    {
        return array('"', '\'');
    }
}
