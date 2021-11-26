<?php

namespace GregOrPlugin\GregOrExchange\Base;

use \GregOrPlugin\GregOrExchange\BaseController;

class Enqueuer extends BaseController
{
    public function register(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        wp_enqueue_style('gregOrEnqueueStyle', sprintf('%sassets/mystyle.css', $this->getPluginUrl()));
        wp_enqueue_script('gregOrEnqueueStyle', sprintf('%sassets/myscript.css', $this->getPluginUrl()));
    }
}
