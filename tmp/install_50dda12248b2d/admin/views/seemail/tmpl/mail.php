<?php 
// author Christophe Weis
global $mainframe;
defined('_JEXEC') or die('Restricted access');
$sitename = $mainframe->getCfg( 'sitename' );
$html = '';
?>
<style type="text/css">
<!--
div.scroll {
height: 100px;
overflow: auto;
border: 1px solid #666;
background-color: #ccc;
}

.form_width {
width: 600px;
padding: 4px 8px;
}
-->
</style> 

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">                    
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Edit E-Mail' ); ?></legend>

		<table class="admintable">
		<tr>
			<td align="right" class="key">
				<label for="event">
					<?php echo JText::_( 'Recipients' ); ?>: <?php echo JHTML::tooltip('Displays a list with all recipients of the e-mail and is not editable', 'Recipients', 'tooltip.png', '', '', false); ?>
				</label>
			</td>
			<td>
                <div class="scroll">
                <div class="form_width">
                    <p><?php foreach ( $this->mails as $mail ) : ?>
                    <?php echo $mail->name; ?> &#60;<?php echo $mail->email; ?>&#62;<br />
                    <?php endforeach; ?>
                    </p>
                </div>
                </div>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="event">
					<?php echo JText::_( 'Subject' ); ?>: 
				</label>
			</td>
			<td>
				<?php $mailSubject = JText::_( 'Invitation from SimpleCalendar on') . ' ' . $sitename . ': ' . $this->event[0]->entryName; ?>
                <input type="text" name="subject" class="form_width" value="<?php echo $mailSubject; ?>"/>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="event">
					<?php echo JText::_( 'E-Mail Body' ); ?>: 
				</label>
			</td>
			<td>
                <?php
                	$html .= '<p>' . JText::_( 'You have been invited to the following event' ) . ':</p>';
                	$link = JURI::root() . 'index.php?option=com_simplecalendar&view=detail&id=' . $this->event[0]->id;
//                	$link = JRoute::_( $link );
                	$html .= JText::_( 'ENTRYNAME' ) . ': ';
                	$html .= '<a href="' . $link . '" alt="' . $this->event[0]->entryName . '">' . $this->event[0]->entryName . '</a>';
                	$html .= '<br />';
                    $html .= JText::_('Date') . ': ';
                    if ( !($this->event[0]->date2) ) {
                        $html .= JHTML::Date($this->event[0]->date1, $this->config->date_long_format);
                    } else {
                        $html .= JText::_('from') . ' ' . JHTML::Date($this->event[0]->date1, $this->config->date_long_format) . ' ' ;
                        $html .= JText::_('until') . ' ' . JHTML::Date($this->event[0]->date2, $this->config->date_long_format);
                    }
                    $html .= '<br />';
					$html .= JText::_( 'ENTRYPLACE' ) . ': ' . $this->event[0]->entryPlace;
                    $editor =& JFactory::getEditor();
                    echo $editor->display('content', $html . $this->event[0]->entryInfo, '550', '400', '60', '20', false);
                ?>
			</td>
		</tr>
	    </table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="invitations" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
