<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\OAuth;

/**
 * Provides constants to the OAuth parameters
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class OAuth
{
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
