<?php

namespace GregOrPlugin\GregOrExchange\Base;

class Activation
{
    public function register(): void
    {
        \register_activation_hook(__FILE__, [$this, 'activate']);
    }

    public function activate(): void
    {
        \flush_rewrite_rules();
    }
}
