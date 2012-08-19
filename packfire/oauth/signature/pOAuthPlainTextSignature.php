<?php
pload('packfire.oauth.pOAuthSignature');
pload('packfire.oauth.pOAuthHelper');

/**
 * pOAuthPlainTextSignature class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.signature
 * @since 1.1-sofia
 */
class pOAuthPlainTextSignature  extends pOAuthSignature {
    
    public function build() {
        $keyParts = pOAuthHelper::urlencode(array(
            $this->consumer->secret(),
            $this->tokenSecret ? $this->tokenSecret : ''
        ));
        
        $key = implode('&', $keyParts);
        return $key;

    }

    public function name() {
        return 'PLAINTEXT';
    }
    
}