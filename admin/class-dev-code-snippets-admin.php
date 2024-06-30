<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://github.com/sandroschutt
 * @since      1.0.0
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/admin
 * @author     Sandro Schutt <sandro@email.com>
 */
class Dev_Code_Snippets_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dev-code-snippets-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dev-code-snippets-admin.js', array('jquery'), $this->version, false);
	}

	function register_highlight_code_menu()
	{
		add_menu_page(
			__('Highlight Code', 'textdomain'),
			__('Highlight Code', 'textdomain'),
			'manage_options',
			'highlight-code',
			array($this, 'highlight_code_admin_page'),
			'dashicons-editor-code',
			20
		);
	}

	function highlight_code_admin_page_display()
	{
?>
		<div class="wrap">
			<h1><?php _e('Highlight Code Snippets', 'textdomain'); ?></h1>
			<a href="<?php echo admin_url('admin.php?page=highlight-code&action=add'); ?>" class="page-title-action"><?php _e('Add New Snippet', 'textdomain'); ?></a>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th scope="col"><?php _e('Snippet Title', 'textdomain'); ?></th>
						<th scope="col"><?php _e('Language', 'textdomain'); ?></th>
						<th scope="col"><?php _e('Shortcode', 'textdomain'); ?></th>
						<th scope="col"><?php _e('Actions', 'textdomain'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					// Fetch the snippets from the database
					global $wpdb;
					$table_name = $wpdb->prefix . 'highlight_code_snippets';
					$snippets = $wpdb->get_results("SELECT * FROM $table_name");

					if ($snippets) {
						foreach ($snippets as $snippet) {
							echo '<tr>';
							echo '<td>' . esc_html($snippet->title) . '</td>';
							echo '<td>' . esc_html($snippet->language) . '</td>';
							echo '<td>[highlight_code id="' . esc_attr($snippet->id) . '"]</td>';
							echo '<td><a href="' . admin_url('admin.php?page=highlight-code&action=edit&id=' . $snippet->id) . '">' . __('Edit', 'textdomain') . '</a> | <a href="' . admin_url('admin.php?page=highlight-code&action=delete&id=' . $snippet->id) . '">' . __('Delete', 'textdomain') . '</a></td>';
							echo '</tr>';
						}
					} else {
						echo '<tr><td colspan="4">' . __('No snippets found.', 'textdomain') . '</td></tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	<?php
	}

	function highlight_code_admin_page()
	{
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		if ($action == 'add' || $action == 'edit') {
			$this->highlight_code_snippet_form($action, $id);
		} elseif ($action == 'delete') {
			global $wpdb;
			$table_name = $wpdb->prefix . 'highlight_code_snippets';
			$wpdb->delete($table_name, ['id' => $id]);
			wp_redirect(admin_url('admin.php?page=highlight-code'));
			exit;
		} else {
			$this->highlight_code_admin_page_display();
		}
	}

	function highlight_code_snippet_form($action, $id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'highlight_code_snippets';
		$snippet = $action == 'edit' ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id)) : null;

	?>
		<div class="wrap">
			<h1><?php echo $action == 'edit' ? __('Edit Snippet', 'textdomain') : __('Add New Snippet', 'textdomain'); ?></h1>
			<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
				<?php wp_nonce_field('save_snippet', 'save_snippet_nonce'); ?>
				<input type="hidden" name="action" value="save_snippet">
				<input type="hidden" name="snippet_id" value="<?php echo esc_attr($id); ?>">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="title"><?php _e('Title', 'textdomain'); ?></label></th>
						<td><input name="title" type="text" id="title" value="<?php echo $snippet ? esc_attr($snippet->title) : ''; ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="language"><?php _e('Language', 'textdomain'); ?></label></th>
						<td>
							<select name="language" id="language">
								<option value="bash" <?php selected($snippet ? $snippet->language : '', 'bash'); ?>>bash</option>
								<option value="c" <?php selected($snippet ? $snippet->language : '', 'c'); ?>>c</option>
								<option value="cpp" <?php selected($snippet ? $snippet->language : '', 'cpp'); ?>>cpp</option>
								<option value="csharp" <?php selected($snippet ? $snippet->language : '', 'csharp'); ?>>csharp</option>
								<option value="css" <?php selected($snippet ? $snippet->language : '', 'css'); ?>>css</option>
								<option value="diff" <?php selected($snippet ? $snippet->language : '', 'diff'); ?>>diff</option>
								<option value="go" <?php selected($snippet ? $snippet->language : '', 'go'); ?>>go</option>
								<option value="graphql" <?php selected($snippet ? $snippet->language : '', 'graphql'); ?>>graphql</option>
								<option value="ini" <?php selected($snippet ? $snippet->language : '', 'ini'); ?>>ini</option>
								<option value="java" <?php selected($snippet ? $snippet->language : '', 'java'); ?>>java</option>
								<option value="javascript" <?php selected($snippet ? $snippet->language : '', 'javascript'); ?>>javascript</option>
								<option value="json" <?php selected($snippet ? $snippet->language : '', 'json'); ?>>json</option>
								<option value="kotlin" <?php selected($snippet ? $snippet->language : '', 'kotlin'); ?>>kotlin</option>
								<option value="less" <?php selected($snippet ? $snippet->language : '', 'less'); ?>>less</option>
								<option value="lua" <?php selected($snippet ? $snippet->language : '', 'lua'); ?>>lua</option>
								<option value="makefile" <?php selected($snippet ? $snippet->language : '', 'makefile'); ?>>makefile</option>
								<option value="markdown" <?php selected($snippet ? $snippet->language : '', 'markdown'); ?>>markdown</option>
								<option value="objectivec" <?php selected($snippet ? $snippet->language : '', 'objectivec'); ?>>objectivec</option>
								<option value="perl" <?php selected($snippet ? $snippet->language : '', 'perl'); ?>>perl</option>
								<option value="php-template" <?php selected($snippet ? $snippet->language : '', 'php-template'); ?>>php-template</option>
								<option value="php" <?php selected($snippet ? $snippet->language : '', 'php'); ?>>php</option>
								<option value="plaintext" <?php selected($snippet ? $snippet->language : '', 'plaintext'); ?>>plaintext</option>
								<option value="python-repl" <?php selected($snippet ? $snippet->language : '', 'python-repl'); ?>>python-repl</option>
								<option value="python" <?php selected($snippet ? $snippet->language : '', 'python'); ?>>python</option>
								<option value="r" <?php selected($snippet ? $snippet->language : '', 'r'); ?>>r</option>
								<option value="ruby" <?php selected($snippet ? $snippet->language : '', 'ruby'); ?>>ruby</option>
								<option value="rust" <?php selected($snippet ? $snippet->language : '', 'rust'); ?>>rust</option>
								<option value="scss" <?php selected($snippet ? $snippet->language : '', 'scss'); ?>>scss</option>
								<option value="shell" <?php selected($snippet ? $snippet->language : '', 'shell'); ?>>shell</option>
								<option value="sql" <?php selected($snippet ? $snippet->language : '', 'sql'); ?>>sql</option>
								<option value="swift" <?php selected($snippet ? $snippet->language : '', 'swift'); ?>>swift</option>
								<option value="typescript" <?php selected($snippet ? $snippet->language : '', 'typescript'); ?>>typescript</option>
								<option value="vbnet" <?php selected($snippet ? $snippet->language : '', 'vbnet'); ?>>vbnet</option>
								<option value="wasm" <?php selected($snippet ? $snippet->language : '', 'wasm'); ?>>wasm</option>
								<option value="xml" <?php selected($snippet ? $snippet->language : '', 'xml'); ?>>xml</option>
								<option value="yaml" <?php selected($snippet ? $snippet->language : '', 'yaml'); ?>>yaml</option>

							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="code"><?php _e('Code', 'textdomain'); ?></label></th>
						<td>
							<textarea name="code" id="code" rows="10" class="large-text"><?php echo $snippet ? wp_unslash($snippet->code) : ''; ?></textarea>
							<p><?php echo __('Avoid using <a href="https://www.php.net/manual/en/language.basic-syntax.phptags.php">PHP tags</a> into this field. The plugin will not save your code if it contains any of it. Other from that, all PHP and HTML code will behave properly. ', 'textarea') ?></p>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Snippet', 'textdomain'); ?>">
				</p>
			</form>

		</div>
<?php
	}

	function handle_snippet_form_submission()
	{
		// Check for nonce security
		if (!isset($_POST['save_snippet_nonce']) || !wp_verify_nonce($_POST['save_snippet_nonce'], 'save_snippet')) {
			wp_die(__('Security check failed', 'textdomain'));
		}

		// Process form data
		global $wpdb;
		$table_name = $wpdb->prefix . 'highlight_code_snippets';

		$id = isset($_POST['snippet_id']) ? intval($_POST['snippet_id']) : 0;
		$title = sanitize_text_field($_POST['title']);
		$language = sanitize_text_field($_POST['language']);
		$code = wp_kses_post($_POST['code']);

		if ($id > 0) {
			// Update existing snippet
			$wpdb->update(
				$table_name,
				[
					'title' => $title,
					'language' => $language,
					'code' => $code
				],
				['id' => $id]
			);
		} else {
			// Insert new snippet
			$wpdb->insert(
				$table_name,
				[
					'title' => $title,
					'language' => $language,
					'code' => $code
				]
			);
		}

		// Redirect back to the snippets page
		wp_redirect(admin_url('admin.php?page=highlight-code'));
		exit;
	}
}
