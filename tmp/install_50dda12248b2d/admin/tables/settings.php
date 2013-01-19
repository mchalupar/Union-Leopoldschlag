<?php
// SimpleCal 0.1
//no direct access
defined('_JEXEC') or die('Restricted access');

class TableSettings extends JTable {

	var $id = '1';
	var $use_gmap = '0';
	var $gmap_api_key = '';
	var $show_donation_line = '1';
	var $date_long_format = null;
	var $date_short_format = null;
	var $time_format = null;
	var $frontend_link_color = null;
//	var $default_ordering = null;
	var $show_search_bar = null;
//	var $show_only_future_events = null;
	var $frontend_add_gid = null;
	var $frontend_edit_gid = null;
	var $gmap_std_latlon = null;
	var $currency = null;
	var $allow_owner_edit = null;
	var $use_jcomments = null;
	var $show_category_color = null;
//	var $allow_file_upload = null;
	var $show_time = null;
	var $frontend_columns = '';
	var $pdf_columns = '';
	var $show_status_color = '1';
	var $show_headers = null;
	var $frontend_auto_publish = null;
	var $map_slider_open = null;
//	var $reverse_sort_order = 0;
	var $show_username = null;
	var $intro_text = null;
	var $delete_on_uninstall = 0;
	var $detailview_registered_only = 1;
	var $custom1_label = '';
	var $custom2_label = '';
	var $show_category_in_detail = 1;
	var $vcal_encoding = '';
	var $show_additionalinfo_label = 1;
	var $allow_unregistered_submission = 0;
	var $use_recaptcha = 0;
	var $recaptcha_public = '';
	var $recaptcha_private = '';
	

	function __construct(&$db) {
		parent::__construct( '#__simplecal_settings', 'id', $db);
	}


	/**
	 * Checks the data before saving
	 *
	 * @return true on success
	 */
	function check() {
		if ( trim($this->id) != '1' ) {
			$this->_error = JText::_( 'ERROR: Only one instance of settings is allowed!' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		if ( !$this->allow_unregistered_submission && trim($this->frontend_add_gid) == '' ) {
			$this->_error = JText::_( 'Error: please select a group with frontend add permissions' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		$this->vcal_encoding = trim($this->vcal_encoding);
		if ($this->vcal_encoding == '' ) {
			$this->vcal_encoding = 'UTF-8';
		}
		return true;
	}
}
?>