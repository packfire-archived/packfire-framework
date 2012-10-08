<?php
namespace Packfire\DateTime;

/**
 * DateTimeFormat class
 * 
 * Date Time standard formats
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
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