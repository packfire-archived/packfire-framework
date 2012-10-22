<?php
namespace Packfire\OAuth\Signature;

use Packfire\OAuth\Helper;
use Packfire\OAuth\Signature;

/**
 * HmacSha1 class
 * 
 * HMAC-SHA1 OAuth Signature Method
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth\Signature
 * @since 1.1-sofia
 */
class HmacSha1 extends Signature {
    
    public function build() {
        $baseString = $this->request->signatureBase();

        $keyParts = Helper::urlencode(array(
            $this->consumer->secret(),
            $this->tokenSecret ? $this->tokenSecret : ''
        ));

        $key = implode('&', $keyParts);

        return base64_encode(hash_hmac('sha1', $baseString, $key, true));
    }

    public function name() {
        return 'HMAC-SHA1';
    }
    
}