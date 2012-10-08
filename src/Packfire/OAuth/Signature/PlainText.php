<?php
namespace Packfire\OAuth\Signature;

use Packfire\OAuth\Helper;
use Packfire\OAuth\Signature;

/**
 * PlainText class
 * 
 * Plain text OAuth signature signing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth\Signature
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