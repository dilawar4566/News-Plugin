<?php
/*
Plugin Name: News Plugin
Description: A simple plugin to manage news articles.
Version: 1.0
Author: Dilawar abbas
*/

class NewsPlugin
{
    public function __construct()
    {
        add_action('init', array($this, 'register_news_post_type'));
        add_action('init', array($this, 'register_shortcodes'));
        add_action('add_meta_boxes', array($this, 'add_author_meta_box'));
        add_action('save_post', array($this, 'save_author_name'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_and_scripts'));
    }

    public function enqueue_styles_and_scripts()
    {
        // Enqueue CSS
        wp_enqueue_style('news-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/news.css', array(), '1.0', 'all');

        // Enqueue JS
        wp_enqueue_script('news-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/news.js', array('jquery'), '1.0', true);
    }

    public function register_news_post_type()
    {
        $labels = array(
            'name' => __('News', 'news-plugin'),
            'singular_name' => __('News', 'news-plugin'),
            'add_new' => __('Add New', 'news-plugin'),
            'add_new_item' => __('Add New News Article', 'news-plugin'),
            'edit_item' => __('Edit News Article', 'news-plugin'),
            'new_item' => __('New News Article', 'news-plugin'),
            'view_item' => __('View News Article', 'news-plugin'),
            'view_items' => __('View News Articles', 'news-plugin'),
            'search_items' => __('Search News Articles', 'news-plugin'),
            'not_found' => __('No News Articles found', 'news-plugin'),
            'not_found_in_trash' => __('No News Articles found in Trash', 'news-plugin'),
            'all_items' => __('All News Articles', 'news-plugin'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'news'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        register_post_type('news', $args);
    }
    // Add the author name meta box.
    public function add_author_meta_box()
    {
        add_meta_box(
            'author_name_meta_box',
            'Author Name',
            array($this, 'author_name_meta_box_content'),
            'news', // Post type to add the meta box to.
            'normal', // Context: 'normal', 'advanced', or 'side'.
            'default' 
        );
    }

    // Content for the author name meta box.
    public function author_name_meta_box_content($post)
    {
        // Retrieve the author name.
        $author_name = get_post_meta($post->ID, '_author_name', true);

        include(plugin_dir_path(__FILE__) . 'template/newinfo.php');
    }

    // Save the author name when the post is saved.
    public function save_author_name($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;

        // Save the author name.
        if (isset($_POST['author_name'])) {
            update_post_meta($post_id, '_author_name', sanitize_text_field($_POST['author_name']));
        }
    }

     // add shortcode.
     public function register_shortcodes(){

		add_shortcode( 'sample-shortcode','shortcode_function'  );
		function shortcode_function(  ) {
            ob_start(); // Start output buffering.
            include(plugin_dir_path(__FILE__) . 'template/news_all_blog.php');    
            return ob_get_clean();
		}
	}
}

// Initialize the plugin.
$news_plugin = new NewsPlugin();
