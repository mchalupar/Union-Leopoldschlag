<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2010 Fabrizio Albonico
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$columns = '';
$isPdf= false;
$showHeaders = false;

// Check to see whether we are printing on screen or on PDF / printer
if (JRequest::getVar('format') == 'pdf') {
	$isPdf= TRUE;
	$columns = $this->config->pdf_columns;
} else {
	$columns = $this->config->frontend_columns;
}

// initialize some counters...
$currentMonthYear = '';
$oldMonthYear = ''; // new to takle error notices

$currentCategory = 0;
$oldCategory = 0;

$i = 0;
if (!isset($html))
	$html = '';
if (!isset($tdStyle))
	$tdStyle = '';
if (!isset($tdClass))
	$tdClass = '';
$colspan = 0;

$column_array = explode(',', $columns);
$column_count = sizeof($column_array);

$colspan = $column_count;
if (!$isPdf) {
	?>
<div id="simplecal">
<span class="buttons"><?php 
if ( !$isPdf && $this->user->id != 0 && $this->user->block == 0 && $this->user->gid >= $this->config->frontend_add_gid ) {
	$link = JRoute::_('index.php?&view=form&task=edit');
	echo SCOutput::showIcon('new', $link);
}

if ( !$this->lists['isPrint']){
	if ( !$isPdf ) {
		if( $this->params->get('linkToPDF', '1') ){
			$pdflink = JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid') . '&format=pdf');
			echo SCOutput::showIcon('pdf', $pdflink);
		}
		if( $this->params->get('linkToPrint', '1') ){
			$printlink = JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid') . '&print=1&tmpl=component');
			echo SCOutput::showIcon('print', $printlink);
		}
		if( $this->params->get('linkToEMail', '1') ){
			echo SCOutput::showIcon('email');
		}
		if( $this->params->get('linkToVCal', '1') ){
			$vcallink = JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid') . '&vcal=1');
			echo SCOutput::showIcon('vcal', $vcallink);
		}
		if ( $this->params->get('linkToRSS', '1') ) {
			$rsslink = JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid') . '&format=feed&type=rss');
			echo SCOutput::showIcon('rss', $rsslink);
		}
		if ( $this->params->get('linkToAtom', '1') ) {
			$rsslink = JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid') . '&format=feed&type=atom');
			echo SCOutput::showIcon('atom', $rsslink);
		}
	}
} else {
	echo SCOutput::showIcon('printpreview');
}
?>
</span>
<?php
}

if ($this->params->get('show_page_title', 1) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?> <?php
if ( $this->lists['isPrint'] ) {
	echo '<br/><br/>'. $this->lists['mainframe']->getCfg( 'sitename' );
}
?> <?php
if ( $this->params->get('intro_text', '') != '' ) {
	echo '<div class="intro_text">' . $this->params->get('intro_text', '') . '</div>';
} elseif ( $this->config->intro_text != '' ) {
	echo '<div class="intro_text">' . $this->config->intro_text . '</div>';
}

$catid_var = JRequest::getInt('catid');
$catid_param = $this->params->get('catid');

if ( $this->config->show_category_color && !$isPdf ) {
	$categories =& $this->get('ActiveCategories');
	$html = '<p class="category_colors">';
	$html .= SCOutput::showCategoryColors($categories);
	$showAll = false;

	if ( ($catid_param  != null || $catid_param  != '0') && $catid_var != null ) {
		$link = JRoute::_( 'index.php?view=calendar' );
		$html .= ' | <a href="' . $link . '">' . JText::_('ALL') . '</a>';
	}
	$html .= '</p>';
	echo $html;
}

if ($isPdf) {
	$tdStyle = '';
	$tdClass = '';
	$html = '<small><table>';
} else {

$highlighter = '';
$highlighter = "<style type=\"text/css\">
div#simplecal tr.sc_highlight {
	background-color: #" . $this->config->frontend_link_color . ";\n";
	
	if( !$this->config->detailview_registered_only || $this->user->id != 0 ) {
		$highlighter .= "	cursor: pointer\n";
	}
	$highlighter .= "}\n</style>";
	
	$this->document->addCustomTag($highlighter);

?>

<form action="<?php echo JRoute::_('index.php?view=calendar&catid=' . JRequest::getString('catid')); ?>" 
	method="post" name="adminForm"><?php if ( $this->config->show_search_bar && !$this->lists['isPrint'] ): ?>
<table width="99%" class="sc_search">
	<tr>
		<td align="left"><?php echo JText::_('Filter'); ?>: <input type="text"
			name="search" id="sc_search" class="sc_search"
			value="<?php echo $this->lists['search'];?>"
			onchange="document.adminForm.submit();" />
		<button class="sc_search" onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
		<button class="sc_search"
			onclick="document.getElementById('sc_search').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
		</td>
		<td align="right"><?php
		echo JText::_('Display Num') .'&nbsp;';
		echo $this->pagination->getLimitBox();
		?></td>
	</tr>
</table>
<?php endif; ?> <?php
$tdClass = 'class="sc_rows"';
$tdStyle = 'style="padding-left: 4px;"';
$html =  '';
$html .= '<table id="sc_table" width="99%">';
}

$td = '';
$thStyle = '';

if ( $this->config->show_headers ) {
	$html .= "<thead>\n";
	$html .= "<tr class=\"sc_column_header\">\n";
	foreach ($column_array as $column) {
		$header = '';
		$columnOutput = array();
		if ( strpos($column, '|') !== false ) {
			$columnOutput = explode('|', $column);
			$thStyle = ' style="width: ' .  $columnOutput[1] . '%;"';
		} else {
			$columnOutput[0] = $column;
			$thStyle = '';
		}
		$columnOutput[0] = trim($columnOutput[0]);
		$header = JText::_('HEADER_' . $columnOutput[0] . '');
		if ( $columnOutput[0] == 'custom1' ) {
			$header = $this->config->custom1_label;
		}
		if ( $columnOutput[0] == 'custom2' ) {
			$header = $this->config->custom2_label;
		}
		$html .= '<th class="sc_column_header"' . $thStyle .'>' . $header . '</th>';
	}
	$html .= '</tr></thead>';
}

// Determine sort ordering
$order = '';
if ( $this->lists['catid'] != 0 ) {
	$order = 'date';
} else {
	if ( $this->params->get('order') == '' && JRequest::getString('order') == '' ) {
		$order = $this->config->default_ordering;
	} else {
		if ( $this->params->get('order') == '' ) {
			$order = JRequest::getString('order');
		} else {
			$order = $this->params->get('order');
		}
	}
}

$body = "<tbody>\n";

foreach ($this->items as $item) {
	$i++;
	$firstRow = '';

	$currentMonthYear = (string) JHTML::_('date', $item->date1, "%m", 0) . JHTML::_('date', $item->date1, "%Y", 0);
	$currentCategory = $item->categoryID;
	$categorycolor = SCOutput::showCategoryColor($item->category_color, $item->categoryName);

	switch ($order) {
		case 'date':
			if ($currentMonthYear != $oldMonthYear) {
				if ( $i != 1 )
				$firstRow = ' style="padding-top: 8px;" ';
				$body .= '<tr><td class="sc_header" '. $firstRow .'colspan="'.$colspan.'"><b>'.JHTML::_('date', $item->date1, "%B %Y", 0).'</b></td></tr>';
			}
			$oldMonthYear = $currentMonthYear;
			break;
		case 'category':
			if ($currentCategory != $oldCategory) {
				if ( $i != 1 )
				$firstRow = 'style="padding-top: 8px;" ';
				$body .= '<tr><td class="sc_header" '. $firstRow .' colspan="'.$colspan.'"><b>'.$item->categoryName.'</b></td></tr>';
			}
			$oldCategory = $currentCategory;
			break;
		default:
			// error text
			echo JText::_('Error! Please check your ordering request!');
			break;
	}

	$link = JRoute::_( 'index.php?option=com_simplecalendar&view=detail&catid=' . $item->catslug . '&id=' . $item->slug );

	$body .= '<tr onmouseover="this.bgColor = \'#' . $this->config->frontend_link_color . '\'';
	if (!$this->config->detailview_registered_only || $this->user->id != 0) {
		$body .= ', this.style.cursor=\'pointer\'" ';
		$body .= 'onclick="document.location.href=\'' . $link . '\'" ';
	} else {
		$body .= '" ';
	}
	$body .= 'onmouseout ="this.bgColor = \'\'" ';
	//	if (!$this->config->detailview_registered_only ) {
	//		$body .= 'onmouseout ="this.bgColor = \'\'" ';
	//	}
	$body .= 'valign="top" >';

	foreach($column_array as $column) {
		$columnOutput = array();
		if ( strpos($column, '|') !== false ) {
			$columnOutput = explode('|', $column);
			$tdStyle = 'style="width: ' .  $columnOutput[1] . '%;"';
		} else {
			$columnOutput[0] = $column;
			$tdStyle = '';
		}

		$body .= "<td $tdClass $tdStyle>";
		if ( SCOutput::decodeColumns(trim($columnOutput[0]), $item) == '' ) {
			$body .= '&nbsp;';
		} else {
			$body .= SCOutput::decodeColumns(trim($columnOutput[0]), $item);
		}
		$body .= '</td>';
	}
	$body .= '</tr>';
}

if ( $i == 0 ) {
	$body .= '</form></table>';
	if ($this->lists['search'] != '') {
		$body .= '<br /><b>' . JText::_('NO_EVENTS_FOUND') . '</b>';
	} else {
		$body .= '<br /><b>' . JText::_('NO_EVENTS') . '</b>';
	}
} else {
	$body .= "</tbody>\n";
	$footer = '';
	if ( !$isPdf && !$this->lists['isPrint']) {
		if ( $this->config->show_search_bar ) {
			$footer .= "<tfoot>\n<tr>\n<td colspan=\"7\" style=\"text-align:center;\">" . $this->pagination->getPagesLinks() . "</td>\n</tr>";
			$footer .= "<tr>\n<td colspan=\"7\" style=\"text-align:center;\">".$this->pagination->getPagesCounter()."</td>\n</tr>\n</tfoot>";
		}
	}
	$body .= "</table>\n";
	$body .= "</form>";
}

echo $html . "\n" . $footer . "\n" . $body;

// Footer. Please do not remove.
if( !$isPdf) {
	echo SCOutput::showFooter();
?>

</div>
	<?php
}
?>
