<?php
namespace Packfire\OAuth;

/**
 * OAuth class
 * 
 * Provides constants to the OAuth parameters
 *
 * @author Sam Yong
 * @copyright Copyright (c) 2012, Sam Yong
 * @license Expression license is undefined on line 8, column 15 in Templates/Scripting/Packfire Framework/PackfireClass.php.
 * @package packfire.oauth
 * @since 1.1-sofia
 */
class OAuth {
    
    const CALLBACK = 'oauth_callback';
    
    const CONSUMER_KEY = 'oauth_consumer_key';
    
    const NONCE = 'oauth_nonce';
    
    const SIGNATURE = 'oauth_signature';
    
    const SIGNATURE_METHOD = 'oauth_signature_method';
    
    const TIMESTAMP = 'oauth_timestamp';
    
    const VERSION = 'oauth_version';
    
    const TOKEN = 'oauth_token';
    
    const TOKEN_SECRET = 'oauth_token_secret';
    
    const VERIFIER = 'oauth_verifier';
    
    const CALLBACK_CONFIRMED = 'oauth_callback_confirmed';
    
}