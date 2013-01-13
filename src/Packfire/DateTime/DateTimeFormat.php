<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\DateTime;

/**
 * Date Time standard formats
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c)  Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class DateTimeFormat {
    
    const ATOM = 'Y-m-d\TH:i:sP';
    const COOKIE = 'l, d-M-y H:i:s T' ;
    const ISO8601 = 'Y-m-d\TH:i:sO' ;
    const RFC822 = 'D, d M y H:i:s O' ;
    const RFC850 = 'l, d-M-y H:i:s T' ;
    const RFC1036 = 'D, d M y H:i:s O' ;
    const RFC1123 = 'D, d M Y H:i:s O' ;
    const RFC2822 = 'D, d M Y H:i:s O' ;
    const RFC3339 = 'Y-m-d\TH:i:sP' ;
    const RSS = 'D, d M Y H:i:s O' ;
    const W3C = 'Y-m-d\TH:i:sP';
    
}