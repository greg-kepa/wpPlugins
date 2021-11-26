<?php

namespace GregOrPlugin\GregOrExchange\PostTypes;

use \GregOrPlugin\GregOrExchange\Base\CustomFields;

class ExchangeRate
{
    private string $postType = 'exchange';

    private array $postDefinition;

    private string $customFieldsPrefix = 'gkex_';

    private array $customFieldsDefinition;

    private CustomFields $customfields;

    public function __construct()
    {
        $this->postDefinition = [
            'public' => true,
            'label' => __('Exchange rates'),
            'description' => 'Exchange rate records',
            'show_in_menu' => 'edit.php',
            'menu_position' => 1,
            'labels' => [
                'name'               => __('Exchange rates'),
                'singular_name'      => __('Exchange rate'),
                'add_new'            => __('Add New Rate'),
                'add_new_item'       => __('Add New Rate'),
                'edit_item'          => __('Edit Rate'),
                'new_item'           => __('Add New Rate'),
                'view_item'          => __('View Exchange rae'),
                'search_items'       => __('Search Rate'),
                'not_found'          => __('No rates found'),
                'not_found_in_trash' => __('No rates found in trash')
            ],
            'supports' => ['title', 'editor', 'revision', 'custom-fields']
        ];
        $this->customFieldsDefinition = [
            [
                'name'          => 'currency_code',
                'title'         => __('Currency code'),
                'description'   => '',
                'type'          => 'text',
                'scope'         => [$this->postType],
                'capability'    => 'edit_posts',
                'attributes'    => 'maxlength="3" size="3" pattern="[A-Z]{3}" required="true"',
            ],
            [
                'name'          => 'rate_value',
                'title'         => __('Exchange rate value'),
                'description'   => '',
                'type'          => 'number',
                'scope'         => [$this->postType],
                'capability'    => 'edit_posts',
                'attributes'    => 'min="0.000001" step="0.000001" required="true"',
            ],
        ];
    }

    public function register(): void
    {
        \add_action('init', [$this, 'createPostType']);
        \register_activation_hook(__FILE__, [$this, 'createPostType']);
        
        $this->customfields = new CustomFields();
        $this->customfields
            ->setPrefix($this->customFieldsPrefix)
            ->setPostTypes([$this->postType])
            ->setFields($this->customFieldsDefinition)
            ->register();
    }

    public function createPostType(): void
    {
        \register_post_type(
            $this->postType,
            $this->postDefinition
        );
    }
}
