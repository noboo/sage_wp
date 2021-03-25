<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);


//background
add_theme_support('custom-background');

// widgetのtext ショートカット
add_filter('widget_text', 'do_shortcode');

// ヘッダー
add_theme_support('custom-header');

//タイトルからキャッチフレーズを削除する
function remove_tagline($title)
{
    if (isset($title['tagline'])) {
        unset($title['tagline']);
    }
    return $title;
}
  add_filter('document_title_parts', 'remove_tagline');

//youtubeをレスポンシブ
function iframe_in_div($the_content)
{
    if (is_singular()) {
        $the_content = preg_replace('/<iframe/i', '<div class="youtube"><iframe', $the_content);
        $the_content = preg_replace('/<\/iframe>/i', '</iframe></div>', $the_content);
    }
    return $the_content;
}
add_filter('the_content', 'iframe_in_div');

// jetpack ギャラリー横幅
if (! isset($content_width)) {
    $content_width = 1140;
}

// jetpack コメント欄削除
function tweakjp_rm_comments_att($open, $post_id)
{
    $post = get_post($post_id);
    if ($post->post_type == 'attachment') {
        return false;
    }
    return $open;
}
add_filter('comments_open', 'tweakjp_rm_comments_att', 10, 2);

// bs4navwalker-bootstrap・navbarドロップダウン
    require_once('views/bs4navwalker.php');

// カスタムHTMLウィジェットでPHP
function widget_text_exec_php($widget_text)
{
    if (strpos($widget_text, '<' . '?') !== false) {
        ob_start();
        eval('?>' . $widget_text);
        $widget_text = ob_get_contents();
        ob_end_clean();
    }
    return $widget_text;
}
add_filter('widget_text', 'widget_text_exec_php', 99);

 // ページャー
 function bootstrap_pagination(\WP_Query $wp_query = null, $echo = true)
 {
     if (null === $wp_query) {
         global $wp_query;
     }
     $pages = paginate_links(
         [
             'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
             'format'       => '?paged=%#%',
             'current'      => max(1, get_query_var('paged')),
             'total'        => $wp_query->max_num_pages,
             'type'         => 'array',
             'show_all'     => false,
             'end_size'     => 1,
             'mid_size'     => 1,
             'prev_next'    => true,
             'prev_text'    => __('« Prev'),
             'next_text'    => __('Next »'),
             'add_args'     => false,
             'add_fragment' => ''
         ]
     );
     if (is_array($pages)) {
         //$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
         $pagination = '<div class="pagination"><ul class="pagination">';
         foreach ($pages as $page) {
             $pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
         }
         $pagination .= '</ul></div>';
         if ($echo) {
             echo $pagination;
         } else {
             return $pagination;
         }
     }
     return null;
 }
 
 // Jetpack's Infinite Scroll
 function infinite_scroll_render()
 {
     while (have_posts()) : the_post();
     get_template_part('templates/partials/content', get_post_type() != 'post' ? get_post_type() : get_post_format());
     //get_template_part('templates/content');
     endwhile;
 }
  function infinite_scroll_init()
  {
      add_theme_support('infinite-scroll', array(
     //   'type'           => 'scroll',
        'footer'         => false,
        'footer_widgets' => false,
        'container'      => 'main', // 投稿を追加するHTML要素のIDを指定
        'wrapper'        => false,
        'render'         => 'infinite_scroll_render',
        'posts_per_page' => false,
    ));
  }
  add_action('init', __NAMESPACE__ . '\\infinite_scroll_init');

// infinite_scroll　文字変更
  function filter_jetpack_infinite_scroll_js_settings($settings)
  {
      $settings['text'] = __('Load more <i class="fas fa-caret-down"></i>', 'l18n');
      return $settings;
  }
add_filter('infinite_scroll_js_settings', 'filter_jetpack_infinite_scroll_js_settings');

// jetpack.cssの読み込みを早く
add_filter('jetpack_implode_frontend_css', '__return_false', 99);

// gutenberg コンテンツ幅変更
add_action('admin_head', function () {
    echo '<style>.wp-block{max-width: 800px !important}</style>'."\n";
});

// reCAPTCHAを使っているページにだけロゴを表示
function remove_recaptcha_badge()
{
    if (!is_page('contact')) {
        wp_deregister_script('google-recaptcha');
    }
}
    add_action('wp_enqueue_scripts', 'remove_recaptcha_badge', 30);
