<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://github.com/sandroschutt
 * @since      1.0.0
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/public
 * @author     Sandro Schutt <sandro@email.com>
 */
class Dev_Code_Snippets_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run function
		 * defined in Dev_Code_Snippets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Code_Snippets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dev-code-snippets-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dev_Code_Snippets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Code_Snippets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dev-code-snippets-public.js', array('jquery'), $this->version, false);

		if ($this->is_shortcode_in_current_content('highlight_code')) {
			if (!wp_script_is('highlightjs', 'enqueued')) {
				wp_enqueue_script('highlightjs', plugin_dir_url(__FILE__) . "/highlight/highlight.min.js");
				wp_enqueue_style('highlightjs', plugin_dir_url(__FILE__) . "/highlight/styles/monokai.min.css");
				wp_enqueue_style('highlight-code-snippets-shortcode', plugin_dir_url(__FILE__) . "/sass/build/shortcode.css");
			}
		}
	}

	function highlight_shortcode_snippet_shortcode($atts)
	{
		remove_filter('the_content', 'wpautop');

		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'my_shortcode'
		);

		$id = $atts['id'];

		global $wpdb;
		$table_name = $wpdb->prefix . 'highlight_code_snippets';
		$snippet = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . intval($id));
		$snippet = $snippet[0];
?>
		<div id="<?php echo esc_html($id) ?>" class="code-snippet-container" title="<?php echo esc_html($snippet->title) ?>">
			<div class="render-content">
				<div class="snippet-header">
					<p>~$: <?php echo $snippet->title ?></p>
					<p class="copy-button">
						<button>
							<span>copiar</span>
						</button>
					</p>
				</div>
				<div>
					<pre>
						<code class="<?php echo "language-" . $snippet->language ?>">
							<?php echo htmlspecialchars(wp_unslash($snippet->code)) ?>
						</code>
					</pre>
				</div>
			</div>
		</div>
<?php
	}

	function is_shortcode_in_current_content($shortcode)
	{
		if (is_front_page() || wp_script_is('highlightjs', 'enqueued')) {
			return false;
		}

		$post_id = get_the_ID();
		$content = get_post_field('post_content', $post_id);

		return has_shortcode($content, $shortcode);
	}
}
