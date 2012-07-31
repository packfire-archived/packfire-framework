<?php
pload('packfire.net.http.pHttpResponse');
pload('packfire.application.IAppResponse');

/**
 * A HTTP Response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.http
 * @since 1.0-sofia
 */
class pHttpAppResponse extends pHttpResponse implements IAppResponse {
    
    public function response(){
        return $this;
    }
    
}