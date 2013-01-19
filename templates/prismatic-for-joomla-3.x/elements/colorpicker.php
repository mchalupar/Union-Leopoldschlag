<?php
/**
 * @package     Joomla ColorPicker Element
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');
$version = new JVersion();

if (version_compare($version->getShortVersion(), '1.6', '<')) {

    jimport( 'joomla.html.parameter.element' );

    class JElementColorpicker extends JElement {

        function fetchElement($name, $value, $node, $control_name) {
            
            JHTML::_('behavior.modal');
            
            JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/colorpicker/css/colorpicker.css');
            JFactory::getDocument()->addScript(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/libs/jquery-1.8.2.min.js');
            JFactory::getDocument()->addScript(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/colorpicker/js/colorpicker.js');
            
            $class = $node->attributes('class') ? $node->attributes('class') : "text_area";
            
            $out .= '<input type="text" name="' . $control_name . '[' . $name . ']" id="' . $control_name . '_' . $name . '" value=""/>';
            
            return $out;
        }

    }

} else {

    jimport('joomla.html.html');
    jimport('joomla.form.formfield');

    class JFormFieldColorpicker extends JFormField {

        
        protected $type = 'Colorpicker';

        protected function getInput() {
            
            JHTML::_('behavior.modal');
            
            JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/colorpicker/css/colorpicker.css');
            JFactory::getDocument()->addScript(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/libs/jquery-1.8.2.min.js');
            JFactory::getDocument()->addScript(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/libs/jquery-noconflict.js');
            JFactory::getDocument()->addScript(JURI::root(true) . '/templates/prismatic-for-joomla-3.x/js/colorpicker/js/colorpicker.js');
            
            $out = '<script type="text/javascript">
xquery("#'.$this->id.'").ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		xquery(el).val(hex);
		xquery(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		xquery(this).ColorPickerSetColor(this.value);
	}
})
.bind("keyup", function(){
	xquery(this).ColorPickerSetColor(this.value);
});
</script>
';
            
            return '<input type="text" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'"/>'.$out;
        }
    }
}