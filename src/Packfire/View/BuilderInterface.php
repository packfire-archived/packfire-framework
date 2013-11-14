<?php

namespace Packfire\View;

interface BuilderInterface
{
    public function create();

    public function build($view);
}
