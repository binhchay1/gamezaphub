<?php
/**
 * File Manager Advanced Main Class
 *
 * @package: File Manager Advanced
 * @Class: fma_main
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'class_fma_main' ) ) {
	return;
}

/**
 * Main Class
 */
class class_fma_main {
	/**
	 * Settings
	 *
	 * @var false|mixed|null $settings Plugin settings.
	 */
	public $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'fma_menus' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'fma_scripts' ) );
		add_action( 'wp_ajax_fma_load_fma_ui', array( &$this, 'fma_load_fma_ui' ) );
		add_action( 'wp_ajax_fma_review_ajax', array( $this, 'fma_review_ajax' ) );
		add_action( 'wp_ajax_fma_validate_php', array( $this, 'fma_validate_php' ) );
		add_action( 'wp_ajax_fma_save_php_file', array( $this, 'fma_save_php_file' ) );
		$this->settings = get_option( 'fmaoptions' );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		// Hook into WordPress to handle slashes in POST data for elFinder
		add_action( 'init', array( $this, 'handle_elfinder_post_data' ) );
	}

	/**
	 * Load Menus
	 */
	public function fma_menus() {
		include 'class_fma_admin_menus.php';
		$fma_menus = new class_fma_admin_menus();
		$fma_menus->load_menus();
	}

	/**
	 * Load File Manager UI
	 */
	public function fma_load_fma_ui() {
		// Handle unescaping for file save operations before passing to connector
		if ( isset( $_POST['cmd'] ) && $_POST['cmd'] === 'put' && isset( $_POST['content'] ) ) {
			$_POST['content'] = wp_unslash( $_POST['content'] );
		}

		include 'class_fma_connector.php';
		$fma_connector = new class_fma_connector();
		if ( wp_verify_nonce( $_REQUEST['_fmakey'], 'fmaskey' ) ) {
			$fma_connector->fma_local_file_system();
		}
	}

	/**
	 * Load Scripts
	 *
	 * @param string $hook The current admin page.
	 */
	public function fma_scripts( $hook ) {
		$locale             = isset( $this->settings['fma_locale'] ) ? sanitize_file_name( $this->settings['fma_locale'] ) : 'en';
		$display_ui_options = isset( $this->settings['display_ui_options'] ) ? $this->settings['display_ui_options'] : FMA_UI;
		$cm_theme           = isset( $this->settings['fma_cm_theme'] ) ? $this->settings['fma_cm_theme'] : 'default';
		$library_url        = FMA_PLUGIN_URL . 'application/library/';
		$hide_path          = false;
		if ( isset( $this->settings['hide_path'] ) && 1 === absint( $this->settings['hide_path'] ) ) {
			$hide_path = true;
		}

		if ( 'toplevel_page_file_manager_advanced_ui' === $hook ) {
			if ( isset( $_GET['page'] ) && 'file_manager_advanced_ui' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_enqueue_style( 'elfinder.jquery-ui', $library_url . 'jquery/jquery-ui.min.css', array(), FMA_VERSION, 'all' );
				wp_enqueue_style( 'elfinder', $library_url . 'css/elfinder.min.css', array(), FMA_VERSION, 'all' );
				wp_enqueue_style( 'elfinder.theme', $library_url . 'css/theme.css', array(), FMA_VERSION, 'all' );
				wp_enqueue_style( 'codemirror', $library_url . 'codemirror/lib/codemirror.css', array(), FMA_VERSION, 'all' );

				if ( isset( $this->settings['fma_theme'] ) && in_array( $this->settings['fma_theme'], array( 'dark', 'grey', 'windows10', 'bootstrap', 'mono' ), true ) ) {
					wp_enqueue_style( 'elfinder.preview', $library_url . 'themes/' . $this->settings['fma_theme'] . '/css/theme.css', array(), FMA_VERSION, 'all' );
				}

				wp_enqueue_style( 'elfinder.styles', FMA_PLUGIN_URL . 'application/assets/css/custom_style_filemanager_advanced.css', array(), FMA_VERSION, 'all' );

				wp_enqueue_script( 'elfinder', $library_url . 'js/elfinder.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-tabs' ), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror', $library_url . 'codemirror/lib/codemirror.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.htmlmixed', $library_url . 'codemirror/mode/htmlmixed/htmlmixed.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.xml', $library_url . 'codemirror/mode/xml/xml.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.css', $library_url . 'codemirror/mode/css/css.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.javascript', $library_url . 'codemirror/mode/javascript/javascript.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.clike', $library_url . 'codemirror/mode/clike/clike.js', array(), FMA_VERSION, true );
				wp_enqueue_script( 'codemirror.php', $library_url . 'codemirror/mode/php/php.js', array(), FMA_VERSION, true );

				if ( 'en' !== $locale ) {
					wp_enqueue_script( 'elfinder.language', $library_url . sprintf( 'js/i18n/elfinder.%s.js', $locale ), array( 'elfinder' ), FMA_VERSION, true );
				}

				if ( 'default' !== $cm_theme ) {
					wp_enqueue_style( 'codemirror.theme', $library_url . 'codemirror/theme/' . $cm_theme . '.css', array(), FMA_VERSION, 'all' );
				}

				wp_enqueue_script( 'elfinder.script', FMA_PLUGIN_URL . 'application/assets/js/elfinder_script.js', array( 'jquery' ), FMA_VERSION, true );
				wp_localize_script(
					'elfinder.script',
					'afm_object',
					array(
						'ajaxurl'   => admin_url( 'admin-ajax.php' ),
						'nonce'     => wp_create_nonce( 'fmaskey' ),
						'locale'    => $locale,
						'ui'        => $display_ui_options,
						'cm_theme'  => $cm_theme,
						'hide_path' => $hide_path,
						'plugin_url' => FMA_PLUGIN_URL,
					)
				);
			}
		}

		wp_register_style( 'afm-jquery.select2', FMA_PLUGIN_URL . 'application/assets/css/select2/jquery.select2.min.css', array(), FMA_VERSION, 'all' );
		wp_register_script( 'afm-jquery.select2', FMA_PLUGIN_URL . 'application/assets/js/select2/jquery.select2.min.js', array( 'jquery' ), FMA_VERSION, true );
		
		if ( in_array( $hook, array( 'file-manager_page_file_manager_advanced_controls', 'file-manager_page_file_manager_advanced_shortcodes', 'file-manager_page_afmp-adminer', 'file-manager_page_afmp-dropbox', 'file-manager_page_afmp-googledrive', 'file-manager_page_afmp-github', 'toplevel_page_file_manager_advanced_ui', 'file-manager_page_afmp-file-logs', 'file-manager_page_afmp-onedrive', 'file-manager_page_afmp-aws' ), true ) ) {
			wp_enqueue_style( 'afm-admin', FMA_PLUGIN_URL . 'application/assets/css/afm-styles.css', array( 'afm-jquery.select2' ), FMA_VERSION, 'all' );
			wp_enqueue_script( 'afm-admin', FMA_PLUGIN_URL . 'application/assets/js/afm-scripts.js', array( 'afm-jquery.select2' ), FMA_VERSION, true );
			wp_localize_script(
				'afm-admin',
				'afmAdmin',
				array(
					'assetsURL' => FMA_PLUGIN_URL . 'application/assets/',
					'jsonURL'  => rest_url(),
				),
			);
		}
	}

	/**
	 * Code Mirror Themes
	 */
	public static function cm_themes() {
		$cm_themes_dir = FMA_CM_THEMES_PATH;
		$cm_themes = [];
		$cm_themes['default'] = array(
			'title' => 'default',
			'pro'   => false,
		);

		$free_themes = array( '3024-day', '3024-night', 'base16-dark', 'base16-light', 'downtown-light' );
		foreach( glob( $cm_themes_dir . '/*.css' ) as $file ) {
			$bn = basename($file, ".css");
			$args = array(
				'title' => $bn,
				'pro'   => true,
			);
			if ( in_array( $bn, $free_themes, true ) ) {
				$args['pro'] = false;
			}
			$cm_themes[ $bn ] = $args;
		}

		usort(
			$cm_themes,
			function( $a, $b ) {
				if ( $a['pro'] === $b['pro'] ) {
					return 0;
				}
				return $a['pro'] ? 1 : -1;
			}
		);

		return $cm_themes;
	}

	/**
	 * Review Ajax
	 */
	public function fma_review_ajax() {
		$nonce = $_REQUEST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'afm_review' ) ) {
			die( __( 'Security check', 'file-manager-advanced' ) );
		} else {
			$task = sanitize_text_field( $_POST['task'] );
			$done = update_option( 'fma_hide_review_section', $task );
			if ( $done ) {
				echo '1';
			} else {
				echo '0';
			}
			die;
		}
	}

	/**
	 * Admin Init
	 *
	 * @since 3.3.1
	 */
	public function admin_init() {
		$is_pro_version = get_option( 'active_plugins', array() );
		if ( in_array( 'file-manager-advanced-pro/file-manager-advanced-shortcode.php', $is_pro_version, true ) ) {
			require_once FMAFILEPATH . 'application/logs/class-filelogs.php';
		}
	}
	/**
	 * PHP Validation Ajax
	 */
	public function fma_validate_php() {
		// Check nonce for security
		if ( ! wp_verify_nonce( $_POST['nonce'], 'fmaskey' ) ) {
			wp_die( __( 'Security check failed', 'file-manager-advanced' ) );
		}

		// Get the PHP code from POST data
		$php_code = wp_unslash( $_POST['php_code'] );
		$filename = sanitize_text_field( $_POST['filename'] );

		// Validate PHP syntax
		$validation_result = $this->validate_php_syntax( $php_code, $filename );

		// Return JSON response
		wp_send_json( $validation_result );
	}

	/**
	 * Validate PHP syntax using php -l
	 *
	 * @param string $php_code The PHP code to validate
	 * @param string $filename The filename for context
	 * @return array Validation result
	 */
	private function validate_php_syntax( $php_code, $filename = 'temp.php' ) {
		$result = array(
			'valid' => true,
			'errors' => array(),
			'message' => ''
		);

		// Create a temporary file to validate
		$temp_file = wp_tempnam( $filename );
		if ( ! $temp_file ) {
			$result['valid'] = false;
			$result['message'] = __( 'Could not create temporary file for validation', 'file-manager-advanced' );
			return $result;
		}

		file_put_contents( $temp_file, $php_code );

		// Only use shell_exec/php -l for validation
		if ( function_exists( 'shell_exec' ) ) {
			$command = 'php -l ' . escapeshellarg( $temp_file ) . ' 2>&1';
			$output = shell_exec( $command );
			// Fallback immediately if php CLI is not found (before unlink and error handling)
			if ( stripos( $output, 'not found' ) !== false || stripos( $output, 'command not found' ) !== false ) {
				unlink( $temp_file );
				$alt_result = $this->validate_php_syntax_alternative( $php_code, $filename );
				$alt_result['message'] = __( 'PHP CLI not found on server. Fallback validation used.', 'file-manager-advanced' ) . ' ' . $alt_result['message'];
				return $alt_result;
			}
			unlink( $temp_file );
			if ( strpos( $output, 'No syntax errors detected' ) !== false ) {
				$result['valid'] = true;
				$result['message'] = __( 'PHP syntax is valid', 'file-manager-advanced' );
			} else {
				$result['valid'] = false;
				$result['errors'][] = array(
					'line' => 0,
					'message' => str_replace( $temp_file, $filename, trim( $output ) ),
					'type' => 'error'
				);
				$result['message'] = __( 'PHP syntax errors found', 'file-manager-advanced' );
			}
			return $result;
		} else {
			unlink( $temp_file );
			// Fallback to alternative validation instead of returning error
			return $this->validate_php_syntax_alternative( $php_code, $filename );
		}
	}

	/**
	 * Alternative PHP syntax validation using token_get_all()
	 *
	 * @param string $php_code The PHP code to validate
	 * @param string $filename The filename for context
	 * @return array Validation result
	 */
	private function validate_php_syntax_alternative( $php_code, $filename = 'temp.php' ) {
		$result = array(
			'valid' => true,
			'errors' => array(),
			'message' => ''
		);

		// First, try using eval() to check syntax (safer approach)
		$syntax_check = $this->check_php_syntax_with_eval( $php_code );
		if ( ! $syntax_check['valid'] ) {
			return $syntax_check;
		}

		// Use output buffering to catch any errors
		ob_start();
		$error_reporting = error_reporting( E_ALL );
		
		// Try to tokenize the PHP code
		$tokens = @token_get_all( $php_code );
		
		if ( $tokens === false ) {
			$result['valid'] = false;
			$result['message'] = __( 'PHP syntax error detected', 'file-manager-advanced' );
			$result['errors'][] = array(
				'line' => 0,
				'message' => __( 'Invalid PHP syntax', 'file-manager-advanced' ),
				'type' => 'error'
			);
		} else {
			// Enhanced validation: check for common syntax issues
			$validation_errors = $this->validate_php_tokens( $tokens );
			
			if ( ! empty( $validation_errors ) ) {
				$result['valid'] = false;
				$result['message'] = __( 'PHP syntax errors detected', 'file-manager-advanced' );
				$result['errors'] = $validation_errors;
			} else {
				$result['message'] = __( 'PHP syntax appears to be valid (basic validation)', 'file-manager-advanced' );
			}
		}
		
		// Restore error reporting
		error_reporting( $error_reporting );
		ob_end_clean();
		
		return $result;
	}

	/**
	 * Check PHP syntax using eval() in a safer way
	 *
	 * @param string $php_code The PHP code to validate
	 * @return array Validation result
	 */
	private function check_php_syntax_with_eval( $php_code ) {
		$result = array(
			'valid' => true,
			'errors' => array(),
			'message' => ''
		);

		// Remove opening PHP tag if present
		$code_to_check = $php_code;
		if ( strpos( $code_to_check, '<?php' ) === 0 ) {
			$code_to_check = substr( $code_to_check, 5 );
		}

		// Set up error handler to catch parse errors
		set_error_handler( function( $severity, $message, $file, $line ) {
			throw new ErrorException( $message, 0, $severity, $file, $line );
		} );

		try {
			// Use eval() to check syntax without executing
			$wrapped_code = "if(false) { $code_to_check }";
			eval( $wrapped_code );
		} catch ( ParseError $e ) {
			$result['valid'] = false;
			$result['message'] = __( 'PHP parse error detected', 'file-manager-advanced' );
			$result['errors'][] = array(
				'line' => $e->getLine(),
				'message' => $e->getMessage(),
				'type' => 'error'
			);
		} catch ( ErrorException $e ) {
			$result['valid'] = false;
			$result['message'] = __( 'PHP syntax error detected', 'file-manager-advanced' );
			$result['errors'][] = array(
				'line' => $e->getLine(),
				'message' => $e->getMessage(),
				'type' => 'error'
			);
		} catch ( Exception $e ) {
			// Other exceptions might indicate syntax issues
			$result['valid'] = false;
			$result['message'] = __( 'PHP error detected', 'file-manager-advanced' );
			$result['errors'][] = array(
				'line' => 0,
				'message' => $e->getMessage(),
				'type' => 'error'
			);
		}

		// Restore error handler
		restore_error_handler();

		return $result;
	}

	/**
	 * Validate PHP tokens for common syntax issues
	 *
	 * @param array $tokens PHP tokens from token_get_all()
	 * @return array Array of validation errors
	 */
	private function validate_php_tokens( $tokens ) {
		$errors = array();
		$bracket_stack = array();
		$brace_stack = array();
		$paren_stack = array();
		$string_stack = array();
		$heredoc_stack = array();
		$comment_open = false;
		$line_number = 1;
		$expect_semicolon = false;
		$last_significant_token = null;

		foreach ( $tokens as $token ) {
			if ( is_array( $token ) ) {
				$token_type = $token[0];
				$token_value = $token[1];
				$line_number = $token[2];

				// Detect unclosed multi-line comment
				if ( $token_type === T_COMMENT || $token_type === T_DOC_COMMENT ) {
					if ( strpos( $token_value, '/*' ) !== false && strpos( $token_value, '*/' ) === false ) {
						$comment_open = $line_number;
					}
					if ( strpos( $token_value, '*/' ) !== false ) {
						$comment_open = false;
					}
					continue;
				}

				// Detect unclosed strings
				if ( $token_type === T_CONSTANT_ENCAPSED_STRING ) {
					$quote = $token_value[0];
					if ( substr_count( $token_value, $quote ) % 2 !== 0 ) {
						$string_stack[] = array('line' => $line_number, 'quote' => $quote);
					}
					continue;
				}

				// Detect unclosed HEREDOC/NOWDOC
				if ( $token_type === T_START_HEREDOC ) {
					$heredoc_stack[] = $line_number;
					continue;
				}
				if ( $token_type === T_END_HEREDOC ) {
					array_pop( $heredoc_stack );
					continue;
				}

				// Check for invalid variable/function/class names
				if ( $token_type === T_VARIABLE ) {
					if ( !preg_match('/^\$[a-zA-Z_][a-zA-Z0-9_]*$/', $token_value ) ) {
						$errors[] = array(
							'line' => $line_number,
							'message' => sprintf( __( 'Invalid variable name: %s', 'file-manager-advanced' ), $token_value ),
							'type' => 'error'
						);
					}
				}
				if ( $token_type === T_FUNCTION || $token_type === T_CLASS ) {
					// Next non-whitespace token should be the name
					$next = next($tokens);
					while ( is_array($next) && ($next[0] === T_WHITESPACE || $next[0] === T_COMMENT || $next[0] === T_DOC_COMMENT) ) {
						$next = next($tokens);
					}
					if ( is_array($next) && isset($next[1]) && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $next[1]) ) {
						$errors[] = array(
							'line' => $line_number,
							'message' => sprintf( __( 'Invalid function/class name: %s', 'file-manager-advanced' ), $next[1] ),
							'type' => 'error'
						);
					}
					// Move pointer back for main foreach
					prev($tokens);
				}

				// Skip whitespace and encapsulated strings
				if ( $token_type === T_WHITESPACE || $token_type === T_ENCAPSED_AND_WHITESPACE ) {
					continue;
				}

				// Check for statements that should end with semicolon
				if ( in_array( $token_type, array( T_REQUIRE_ONCE, T_REQUIRE, T_INCLUDE_ONCE, T_INCLUDE, T_ECHO, T_PRINT, T_RETURN ) ) ) {
					$expect_semicolon = true;
				}

				// Check for function calls, variable assignments, etc.
				if ( $token_type === T_STRING && $last_significant_token &&
					 ! in_array( $last_significant_token, array( T_FUNCTION, T_CLASS, T_CONST, T_NEW ) ) ) {
					$expect_semicolon = true;
				}

				$last_significant_token = $token_type;
			} else {
				$token_value = $token;

				// Count brackets, braces, and parentheses with line tracking
				switch ( $token_value ) {
					case '[':
						$bracket_stack[] = $line_number;
						break;
					case ']':
						if ( empty( $bracket_stack ) ) {
							$errors[] = array(
								'line' => $line_number,
								'message' => __( "Unmatched ']' found here. Check for missing opening '[' or misplaced closing ']' above this line.", 'file-manager-advanced' ),
								'type' => 'error'
							);
						} else {
							array_pop( $bracket_stack );
						}
						break;
					case '{':
						$brace_stack[] = $line_number;
						$expect_semicolon = false;
						break;
					case '}':
						if ( empty( $brace_stack ) ) {
							$errors[] = array(
								'line' => $line_number,
								'message' => __( "Unmatched '}' found here. Check for missing opening '{' or misplaced closing '}' above this line.", 'file-manager-advanced' ),
								'type' => 'error'
							);
						} else {
							array_pop( $brace_stack );
						}
						$expect_semicolon = false;
						break;
					case '(': 
						$paren_stack[] = $line_number;
						break;
					case ')':
						if ( empty( $paren_stack ) ) {
							$errors[] = array(
								'line' => $line_number,
								'message' => __( "Unmatched ')' found here. Check for missing opening '(' or misplaced closing ')' above this line.", 'file-manager-advanced' ),
								'type' => 'error'
							);
						} else {
							array_pop( $paren_stack );
						}
						break;
					case ';':
						$expect_semicolon = false;
						break;
				}

				// Check for missing semicolon before certain tokens
				if ( $expect_semicolon && in_array( $token_value, array( '{', '}' ) ) ) {
					$errors[] = array(
						'line' => $line_number,
						'message' => __( 'Missing semicolon before', 'file-manager-advanced' ) . ' ' . $token_value,
						'type' => 'error'
					);
					$expect_semicolon = false;
				}
			}
		}

		// Check for unclosed brackets, braces, or parentheses with line info
		foreach ( $bracket_stack as $ln ) {
			$errors[] = array(
				'line' => $ln,
				'message' => __( 'Unclosed square bracket opened here', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		foreach ( $brace_stack as $ln ) {
			$errors[] = array(
				'line' => $ln,
				'message' => __( 'Unclosed curly brace opened here', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		foreach ( $paren_stack as $ln ) {
			$errors[] = array(
				'line' => $ln,
				'message' => __( 'Unclosed parenthesis opened here', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		// Check for unclosed HEREDOC/NOWDOC
		foreach ( $heredoc_stack as $ln ) {
			$errors[] = array(
				'line' => $ln,
				'message' => __( 'Unclosed HEREDOC/NOWDOC block opened here', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		// Check for unclosed strings
		foreach ( $string_stack as $str ) {
			$errors[] = array(
				'line' => $str['line'],
				'message' => sprintf( __( 'Unclosed string (started with %s)', 'file-manager-advanced' ), $str['quote'] ),
				'type' => 'error'
			);
		}
		// Check for unclosed multi-line comment
		if ( $comment_open ) {
			$errors[] = array(
				'line' => $comment_open,
				'message' => __( 'Unclosed multi-line comment opened here', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		// Check for missing semicolon at the end
		if ( $expect_semicolon ) {
			$errors[] = array(
				'line' => $line_number,
				'message' => __( 'Missing semicolon at end of statement', 'file-manager-advanced' ),
				'type' => 'error'
			);
		}
		return $errors;
	}

	/**
	 * Check if PHP CLI is available
	 *
	 * @return bool
	 */
	private function is_php_cli_available() {
		// Check if exec function is available
		if ( ! function_exists( 'exec' ) ) {
			return false;
		}
		
		$output = array();
		$return_code = 0;
		exec( 'php --version 2>&1', $output, $return_code );

		// Fallback immediately if php CLI is not found (before unlink and error handling)
		if ( stripos( $output, 'not found' ) !== false || stripos( $output, 'command not found' ) !== false ) {
			unlink( $temp_file );
			$alt_result = $this->validate_php_syntax_alternative( $php_code, $filename );
			$alt_result['message'] = __( 'PHP CLI not found on server. Fallback validation used.', 'file-manager-advanced' ) . ' ' . $alt_result['message'];
			return $alt_result;
		}
		return $return_code === 0;
	}

	/**
	 * Parse PHP error output
	 *
	 * @param array $output Error output from php -l
	 * @param string $temp_file Temporary file path
	 * @param string $actual_filename Actual filename
	 * @return array Parsed errors
	 */
	private function parse_php_errors( $output, $temp_file, $actual_filename ) {
		$errors = array();
		
		foreach ( $output as $line ) {
			// Replace temp file path with actual filename
			$line = str_replace( $temp_file, $actual_filename, $line );
			
			// Parse error line to extract line number and error message
			if ( preg_match( '/.*in\s+(.+)\s+on\s+line\s+(\d+)/', $line, $matches ) ) {
				$errors[] = array(
					'line' => intval( $matches[2] ),
					'message' => trim( $line ),
					'type' => 'error'
				);
			} else if ( !empty( trim( $line ) ) && strpos( $line, 'Errors parsing' ) === false ) {
				$errors[] = array(
					'line' => 0,
					'message' => trim( $line ),
					'type' => 'error'
				);
			}
		}
		
		return $errors;
	}

	/**
	 * Save PHP file with proper unescaping
	 */
	public function fma_save_php_file() {
		// Check nonce for security
		if ( ! wp_verify_nonce( $_POST['nonce'], 'fmaskey' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', 'file-manager-advanced' ) ) );
			return;
		}

		// Get the PHP code and file info from POST data
		$php_code = wp_unslash( $_POST['php_code'] );
		$file_hash = sanitize_text_field( $_POST['file_hash'] );
		$filename = sanitize_text_field( wp_unslash( $_POST['filename'] ) );

		// Skip validation since this is called when user chooses "Save Anyway"
		// The validation was already done and user explicitly chose to save with errors

		try {
			// Store original POST data
			$original_post = $_POST;
			
			// Set up POST data for elFinder save operation
			$_POST = array(
				'cmd' => 'put',
				'target' => $file_hash,
				'content' => $php_code, // Already unslashed above
				'action' => 'fma_load_fma_ui',
				'_fmakey' => wp_create_nonce( 'fmaskey' )
			);

			// Use elFinder connector to save the file
			if ( ! class_exists( 'class_fma_connector' ) ) {
				include_once 'class_fma_connector.php';
			}
			
			if ( class_exists( 'class_fma_connector' ) ) {
				$fma_connector = new class_fma_connector();
				
				// Capture elFinder output
				ob_start();
				$fma_connector->fma_local_file_system();
				$elfinder_response = ob_get_clean();

				// Restore original POST data
				$_POST = $original_post;

				// Parse elFinder response
				$response_data = json_decode( $elfinder_response, true );
				
				if ( $response_data && isset( $response_data['changed'] ) && ! empty( $response_data['changed'] ) ) {
					wp_send_json_success( array( 
						'message' => __( 'File saved successfully', 'file-manager-advanced' ),
						'elfinder_response' => $response_data
					) );
				} else if ( $response_data && ! isset( $response_data['error'] ) ) {
					// Sometimes elFinder doesn't return 'changed' but save is successful
					wp_send_json_success( array( 
						'message' => __( 'File saved successfully', 'file-manager-advanced' ),
						'elfinder_response' => $response_data
					) );
				} else {
					$error_message = '';
					if ( $response_data && isset( $response_data['error'] ) ) {
						$error_message = $response_data['error'];
					} else {
						$error_message = __( 'Failed to save file through elFinder', 'file-manager-advanced' );
					}
					
					wp_send_json_error( array( 
						'message' => $error_message,
						'elfinder_response' => $elfinder_response,
						'debug_info' => $response_data
					) );
				}
			} else {
				// Restore original POST data
				$_POST = $original_post;
				
				wp_send_json_error( array( 
					'message' => __( 'elFinder connector class not found', 'file-manager-advanced' )
				) );
			}
			
		} catch ( Exception $e ) {
			// Restore original POST data in case of exception
			$_POST = $original_post;
			
			wp_send_json_error( array( 
				'message' => sprintf( __( 'Error saving file: %s', 'file-manager-advanced' ), $e->getMessage() ),
				'exception' => $e->getMessage()
			) );
		}
	}

	/**
	 * Handle elFinder POST data to remove WordPress slashes
	 */
	public function handle_elfinder_post_data() {
		// Only process on admin AJAX requests for our file manager
		if ( ! is_admin() || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			return;
		}

		// Check if this is our elFinder request
		if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'fma_load_fma_ui' ) {
			return;
		}

		// Check if this is a file content save operation
		if ( isset( $_POST['cmd'] ) && $_POST['cmd'] === 'put' && isset( $_POST['content'] ) ) {
			// Remove slashes from content before elFinder processes it
			$_POST['content'] = wp_unslash( $_POST['content'] );
		}
	}

	public static function has_pro() {
		$has_pro = apply_filters( 'fma__has_pro', false );
	   return $has_pro;
	}
}


