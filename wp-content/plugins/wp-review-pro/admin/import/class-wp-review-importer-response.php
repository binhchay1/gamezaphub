<?php
/**
 * Class WP_Review_Importer_Response
 *
 * @package WP_Review
 */

/**
 * Class WP_Review_Importer_Response
 */
class WP_Review_Importer_Response {

	/**
	 * Message.
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Is done.
	 *
	 * @var bool
	 */
	private $is_done;

	/**
	 * Offset.
	 *
	 * @var int
	 */
	private $offset;

	/**
	 * Is error.
	 *
	 * @var bool
	 */
	private $is_error;

	/**
	 * WP_Review_Importer_Response constructor.
	 *
	 * @param string $message  Message.
	 * @param bool   $is_done  Is done.
	 * @param int    $offset   Offset.
	 * @param bool   $is_error Is error.
	 */
	public function __construct( $message, $is_done = true, $offset = 0, $is_error = false ) {
		$this->message  = $message;
		$this->is_done  = $is_done;
		$this->offset   = $offset;
		$this->is_error = $is_error;
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'message'  => $this->message,
			'is_done'  => $this->is_done,
			'offset'   => $this->offset,
			'is_error' => $this->is_error,
		);
	}

	/**
	 * Is error?
	 *
	 * @return bool
	 */
	public function is_error() {
		return $this->is_error;
	}
}
