<?php
pload('pHttpResponse');
pload('pHttpResponseCode');

/**
 * pRedirectResponse Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pRedirectResponse extends pHttpResponse {
    
    public function __construct($url, $code = pHttpResponseCode::HTTP_302){
        parent::__construct();
        $this->code($code);
        $this->headers()->add('Location', $url);
    }
    
}