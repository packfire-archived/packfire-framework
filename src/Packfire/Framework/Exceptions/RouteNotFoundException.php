<?php /*
 * Copyright (C) 2014 Sam-Mauris Yong. All rights reserved.
 * This file is part of the Packfire Framework project, which is released under New BSD 3-Clause license.
 * See file LICENSE or go to http://opensource.org/licenses/BSD-3-Clause for full license details.
 */

namespace Packfire\Framework\Exceptions;

use \Exception;

class RouteNotFoundException extends Exception
{
    public function __construct($key)
    {
        parent::__construct('The route "' . $key . '" could not be found in the router, loaded from the configuration.');
    }
}
