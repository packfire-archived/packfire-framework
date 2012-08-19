<?php
pload('packfire.oauth.pOAuthSignature');

/**
 * pOAuthHmacSha1Signature class
 * 
 * HMAC-SHA1 OAuth Signature Method
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.signature
 * @since 1.1-sofia
 */
class pOAuthHmacSha1Signature extends pOAuthSignature {
    
    public function build() {
        $baseString = $this->request->baseSignature();

        $keyParts = pOAuthHelper::urlencode(array(
            $this->consumer->secret(),
            $this->tokenSecret ? pOAuthHelper::urlencode($this->tokenSecret) : ''
        ));

        $key = implode('&', $keyParts);

        return base64_encode(hash_hmac('sha1', $baseString, $key, true));
    }

    public function name() {
        return 'HMAC-SHA1';
    }
    
}