<?php

namespace GregOrPlugin\GregOrExchange\Base;

class CustomFields
{
    /**
    * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
    */
    private string $prefix = 'gke_';

    /**
    * @var  array  $postTypes  An array of public custom post types,
    *   plus the standard "post" and "page" - add the custom types you want to include here
    */
    private array $postTypes = ['post', 'page'];
    /**
    * @var  array  $fields  Defines the custom fields available
    */
    private array $fields = [
        [
            'name'          => 'block-of-text',
            'title'         => 'A block of text',
            'description'   => '',
            'type'          => 'textarea',
            'scope'         => ['page'],
            'capability'    => 'edit_pages',
            'attributes'    => '',
        ],
        [
            'name'          => 'short-text',
            'title'         => 'A short bit of text',
            'description'   => '',
            'type'          =>   'text',
            'scope'         =>  ['post'],
            'capability'    => 'edit_posts',
            'attributes'    => '',
        ],
        [
            'name'          => 'checkbox',
            'title'         => 'Checkbox',
            'description'   => '',
            'type'          => 'checkbox',
            'scope'         =>  ['post', 'page'],
            'capability'    => 'manage_options',
            'attributes'    => '',
        ]
    ];

    public function register(): CustomFields
    {
        \add_action('admin_menu', [$this, 'createCustomFields']);
        \add_action('save_post', [$this, 'saveCustomFields'], 1, 2);
        // Comment this line out if you want to keep default custom fields meta box
        \add_action('do_meta_boxes', [$this, 'removeDefaultCustomFields'], 10, 3);
        
        return $this;
    }

    public function setPrefix(string $prefix): CustomFields
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setPostTypes(array $postTypes): CustomFields
    {
        $this->postTypes = $postTypes;

        return $this;
    }

    public function setFields(array $fields): CustomFields
    {
        $this->fields = $fields;

        return $this;
    }

    /**
    * Remove the default Custom Fields meta box
    */
    public function removeDefaultCustomFields($type, $context, $post): void
    {
        foreach (array( 'normal', 'advanced', 'side' ) as $context) {
            foreach ($this->postTypes as $postType) {
                \remove_meta_box('postcustom', $postType, $context);
            }
        }
    }
    /**
    * Create the new Custom Fields meta box
    */
    public function createCustomFields(): void
    {
        if (function_exists('add_meta_box')) {
            foreach ($this->postTypes as $postType) {
                \add_meta_box(
                    'my-custom-fields',
                    'Custom Fields',
                    [$this, 'displayCustomFields'],
                    $postType,
                    'normal',
                    'high'
                );
            }
        }
    }
    /**
    * Display the new Custom Fields meta box
    */
    public function displayCustomFields(): void
    {
        global $post;
        echo PHP_EOL . '<div class="form-wrap">';
        \wp_nonce_field('my-custom-fields', 'my-custom-fields_wpnonce', false, true);
        foreach ($this->fields as $customField) {
            // Check scope
            $scope = $customField['scope'];
            $output = false;
            foreach ($scope as $scopeItem) {
                switch ($scopeItem) {
                    default:
                        if ($post->post_type == $scopeItem) {
                            $output = true;
                        }
                        break;
                }
                if ($output) {
                    break;
                }
            }
            // Check capability
            if (!\current_user_can($customField['capability'], $post->ID)) {
                $output = false;
            }
            // Output if allowed
            if ($output) {
                $customFieldName = $this->prefix . $customField['name'];
                $customFieldValue = \get_post_meta($post->ID, $customFieldName, true);
                echo '<div class="form-field form-required">';
                echo '<label for="' . $customFieldName
                    . '"><b>' . $customField['title']
                    . '</b></label>';
                switch ($customField['type']) {
                    case "number":
                        echo '<input type="number" name="' . $customFieldName
                            . '" id="' . $customFieldName
                            . '" value="'
                            . \htmlspecialchars($customFieldValue)
                            . '" ' . $customField['attributes'] . ' />';
                        break;
                    case "checkbox":
                        // Checkbox
                        echo '<input type="checkbox" name="' . $customFieldName
                            . '" id="' . $customFieldName
                            . '" value="yes"'
                            . ' ' . $customField['attributes'];
                        if ($customFieldValue == "yes") {
                            echo ' checked="checked"';
                        }
                        echo '" style="width: auto;" />';
                        break;
                    case "textarea":
                    case "wysiwyg":
                        // Text area
                        echo '<textarea name="' . $customFieldName
                            . '" id="' . $customFieldName
                            . '" columns="30" rows="3" '. $customField['attributes'] . '>'
                            . \htmlspecialchars($customFieldValue)
                            . '</textarea>';
                        // WYSIWYG
                        if ($customField[ 'type' ] == "wysiwyg") {
                            echo  PHP_EOL . '<script type="text/javascript">'
                                . PHP_EOL . '    jQuery( document ).ready( function() { '
                                . PHP_EOL . '        jQuery( "'
                                . $customFieldName
                                . '" ).addClass( "mceEditor" );'
                                . PHP_EOL . '          if (typeof( tinyMCE ) == "object" '
                                . PHP_EOL . '               && typeof( tinyMCE.execCommand ) == "function" '
                                . PHP_EOL . '           ) {'
                                . PHP_EOL . '               tinyMCE.execCommand( "mceAddControl", false, "'
                                . $customFieldName
                                . '" );'
                                . PHP_EOL . '           }'
                                . PHP_EOL . '    });'
                                . PHP_EOL . '</script>';
                        }
                        break;
                    default:
                        // Plain text field
                        echo '<input type="text" name="' . $customFieldName
                            . '" id="' . $customFieldName
                            . '" value="'
                            . \htmlspecialchars($customFieldValue)
                            . '" ' . $customField['attributes'] . ' />';
                        break;
                }
                if ($customField['description']) {
                    echo PHP_EOL . '<p>' . $customField['description'] . '</p>';
                }
                echo PHP_EOL . '</div>';
            }
        }
        echo PHP_EOL . '</div>';
    }
    /**
    * Save the new Custom Fields values
    */
    public function saveCustomFields($post_id, $post)
    {
        if (!isset($_POST['my-custom-fields_wpnonce'])
            || !\wp_verify_nonce($_POST[ 'my-custom-fields_wpnonce'], 'my-custom-fields')
        ) {
            return;
        }
        if (!\current_user_can('edit_post', $post_id)) {
            return;
        }
        if (!\in_array($post->post_type, $this->postTypes)) {
            return;
        }
        foreach ($this->fields as $customField) {
            $customFieldName = $this->prefix . $customField['name'];
            if (\current_user_can($customField['capability'], $post_id)) {
                if (isset($_POST[$customFieldName])
                    && \trim($_POST[$customFieldName])
                ) {
                    $value = $_POST[ $customFieldName ];
                    // Auto-paragraphs for any WYSIWYG
                    if ($customField['type'] == "wysiwyg") {
                        $value = \wpautop($value);
                    }
                    \update_post_meta($post_id, $customFieldName, $value);
                } else {
                    \delete_post_meta($post_id, $customFieldName);
                }
            }
        }
    }
}
