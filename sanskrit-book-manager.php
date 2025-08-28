<?php
/*
Plugin Name: Sanskrit Book Manager
Description: Adds a "Books" post type with custom fields for PDF URL, author, pages, file size, and other details.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit; // Security measure

// 1. Create the "Book" Custom Post Type
function sbm_create_book_post_type() {
    $labels = [
        'name'               => 'Books',
        'singular_name'      => 'Book',
        'menu_name'          => 'Books',
        'add_new_item'       => 'Add New Book',
        'edit_item'          => 'Edit Book',
        'view_item'          => 'View Book',
        'all_items'          => 'All Books',
        'search_items'       => 'Search Books',
        'not_found'          => 'No books found.',
        'not_found_in_trash' => 'No books found in Trash.',
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'books'],
        'supports'           => ['title', 'editor', 'thumbnail'], // Title, Description, and Book Cover (Featured Image)
        'menu_icon'          => 'dashicons-book-alt',
        'show_in_rest'       => true,
    ];
    register_post_type('book', $args);
}
add_action('init', 'sbm_create_book_post_type');

// 2. Create Custom Taxonomies for better organization
function sbm_create_book_taxonomies() {
    register_taxonomy('language', 'book', ['label' => 'Languages', 'rewrite' => ['slug' => 'language'], 'hierarchical' => false]);
    register_taxonomy('author', 'book', ['label' => 'Authors', 'rewrite' => ['slug' => 'author'], 'hierarchical' => false]);
    register_taxonomy('publisher', 'book', ['label' => 'Publishers', 'rewrite' => ['slug' => 'publisher'], 'hierarchical' => false]);
}
add_action('init', 'sbm_create_book_taxonomies');


// 3. Add the Custom Fields Meta Box
function sbm_add_details_meta_box() {
    add_meta_box(
        'sbm_book_details', // ID
        'Book Details',     // Title
        'sbm_meta_box_html',// Callback function
        'book',             // Post type
        'normal',           // Context
        'high'              // Priority
    );
}
add_action('add_meta_boxes', 'sbm_add_details_meta_box');

// 4. The HTML for the Meta Box
function sbm_meta_box_html($post) {
    // Get saved values
    $pdf_url = get_post_meta($post->ID, '_sbm_pdf_url', true);
    $file_size = get_post_meta($post->ID, '_sbm_file_size', true);
    $total_pages = get_post_meta($post->ID, '_sbm_total_pages', true);
    $editor_name = get_post_meta($post->ID, '_sbm_editor_name', true);
    $printer_name = get_post_meta($post->ID, '_sbm_printer_name', true);
    $print_place = get_post_meta($post->ID, '_sbm_print_place', true);
    $source_name = get_post_meta($post->ID, '_sbm_source_name', true);
    $source_url = get_post_meta($post->ID, '_sbm_source_url', true);
    $buy_url = get_post_meta($post->ID, '_sbm_buy_url', true);
    $pub_year = get_post_meta($post->ID, '_sbm_pub_year', true);

    wp_nonce_field('sbm_save_book_details', 'sbm_nonce');
    ?>
    <style> .sbm-field { margin-bottom: 15px; } .sbm-field label { display: block; font-weight: bold; margin-bottom: 5px; } .sbm-field input, .sbm-field textarea { width: 100%; } </style>
    
    <div class="sbm-field">
        <label for="sbm_pdf_url">PDF URL (or Upload)</label>
        <input type="text" id="sbm_pdf_url" name="sbm_pdf_url" value="<?php echo esc_url($pdf_url); ?>">
        <button type="button" id="sbm_upload_btn" class="button">Upload PDF</button>
        <p><em>Enter the direct URL to the PDF or upload one. This will be used for the "Download" button.</em></p>
    </div>

    <div class="sbm-field">
        <label for="sbm_buy_url">Buy Now URL</label>
        <input type="text" id="sbm_buy_url" name="sbm_buy_url" value="<?php echo esc_url($buy_url); ?>" placeholder="e.g., Amazon link">
    </div>
    
    <hr>
    
    <div class="sbm-field">
        <label for="sbm_file_size">File Size (e.g., 25.5 MB)</label>
        <input type="text" id="sbm_file_size" name="sbm_file_size" value="<?php echo esc_attr($file_size); ?>">
    </div>

    <div class="sbm-field">
        <label for="sbm_total_pages">Total Pages</label>
        <input type="number" id="sbm_total_pages" name="sbm_total_pages" value="<?php echo esc_attr($total_pages); ?>">
    </div>

    <div class="sbm-field">
        <label for="sbm_pub_year">Year of Publication</label>
        <input type="text" id="sbm_pub_year" name="sbm_pub_year" value="<?php echo esc_attr($pub_year); ?>">
    </div>

    <div class="sbm-field">
        <label for="sbm_editor_name">Editor</label>
        <input type="text" id="sbm_editor_name" name="sbm_editor_name" value="<?php echo esc_attr($editor_name); ?>">
    </div>
    
    <div class="sbm-field">
        <label for="sbm_printer_name">Printer</label>
        <input type="text" id="sbm_printer_name" name="sbm_printer_name" value="<?php echo esc_attr($printer_name); ?>">
    </div>

    <div class="sbm-field">
        <label for="sbm_print_place">Place of Printing</label>
        <input type="text" id="sbm_print_place" name="sbm_print_place" value="<?php echo esc_attr($print_place); ?>">
    </div>
    
    <hr>
    
    <div class="sbm-field">
        <label for="sbm_source_name">Source Name (e.g., Internet Archive)</label>
        <input type="text" id="sbm_source_name" name="sbm_source_name" value="<?php echo esc_attr($source_name); ?>">
    </div>
    
    <div class="sbm-field">
        <label for="sbm_source_url">Source URL</label>
        <input type="text" id="sbm_source_url" name="sbm_source_url" value="<?php echo esc_url($source_url); ?>">
    </div>

    <script>
        jQuery(document).ready(function($){
            $('#sbm_upload_btn').click(function(e) {
                e.preventDefault();
                var uploader = wp.media({
                    title: 'Select or Upload PDF',
                    button: { text: 'Use this PDF' },
                    multiple: false,
                    library: { type: 'application/pdf' }
                }).on('select', function() {
                    var attachment = uploader.state().get('selection').first().toJSON();
                    $('#sbm_pdf_url').val(attachment.url);
                }).open();
            });
        });
    </script>
    <?php
}

// 5. Save the Custom Fields Data
function sbm_save_book_details($post_id) {
    if (!isset($_POST['sbm_nonce']) || !wp_verify_nonce($_POST['sbm_nonce'], 'sbm_save_book_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_sbm_pdf_url'      => 'sbm_pdf_url',
        '_sbm_file_size'    => 'sbm_file_size',
        '_sbm_total_pages'  => 'sbm_total_pages',
        '_sbm_editor_name'  => 'sbm_editor_name',
        '_sbm_printer_name' => 'sbm_printer_name',
        '_sbm_print_place'  => 'sbm_print_place',
        '_sbm_source_name'  => 'sbm_source_name',
        '_sbm_source_url'   => 'sbm_source_url',
        '_sbm_buy_url'      => 'sbm_buy_url',
        '_sbm_pub_year'     => 'sbm_pub_year',
    ];

    foreach ($fields as $meta_key => $post_key) {
        if (isset($_POST[$post_key])) {
            $value = ($post_key === 'sbm_pdf_url' || $post_key === 'sbm_buy_url' || $post_key === 'sbm_source_url')
                ? esc_url_raw($_POST[$post_key])
                : sanitize_text_field($_POST[$post_key]);
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
add_action('save_post', 'sbm_save_book_details');

// 6. Enqueue media uploader script
function sbm_enqueue_admin_scripts() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'sbm_enqueue_admin_scripts');
