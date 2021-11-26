<?php

namespace GregOrPlugin\GregOrExchange\Base;

class Deactivation
{
    public function register(): void
    {
        \register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }

    public function deactivate(): void
    {
        \flush_rewrite_rules();
    }
}
