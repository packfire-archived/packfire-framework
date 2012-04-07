<?php
pload('pHttpResponse');
pload('pHttpResponseCode');

/**
 * pRedirectResponse Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pRedirectResponse extends pHttpResponse {
    
    /**
     * Create a new pRedirectResponse object
     * @param string|pUrl $url The URL to redirect to
     * @param string $code (optional) The HTTP code to use for the redirect.
     *                      Defaults to pHttpResponseCode::HTTP_302.
     * @since 1.0-sofia
     */
    public function __construct($url, $code = pHttpResponseCode::HTTP_302){
        parent::__construct();
        $this->code($code);
        $this->headers()->add('Location', (string)$url);
    }
    
}