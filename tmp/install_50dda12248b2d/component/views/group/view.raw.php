<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class SimpleCalendarViewGroup extends JView {

	function display($tpl = null) {
		$model = $this->getModel('group');
//		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
//		JArrayHelper::toInteger($cids);
//		$cid = $cids[0];
//		$item =& $model->getData($cid);
		$item =& $model->getData();

		if ( !empty($item) ) {
		// Return the array the AJAX call is expecting
			echo $item->contactName . "<br>" . $item->contactEmail . "<br>" . $item->contactWebSite . "<br>" .  $item->contactTelephone . "<br>" . $item->groupLatLon;
		} else {
			echo "<br><br><br><br><br>";
		}
	}
}
?>