<?php
/**
 * @version 0.7
 * @package Joomla
 * @subpackage SimpleCalendar
 * @copyright (C) 2008-2009 Fabrizio Albonico
 * @license GNU/GPL, see com_simplecalendar_LICENSE.txt
 * SimpleCalendar is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * SimpleCalendar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with SimpleCalendar; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

class SCUpload {

	/**
	 * Returns a list of allowed extensions
	 *
	 * @return array extension list
	 */
	function allowedTypes() {
		$allowed_types = array(
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'txt' => 'text/plain',
			'zip' => 'application/zip',
			'doc' => 'application/msword',
			'odt' => 'application/vnd.oasis.opendocument.text',
			'pdf' => 'application/pdf',
			'ppt' => 'application/vnd.ms-powerpoint',
			'xls' => 'application/vnd.ms-excel'
			);
			/*
			 * Author's note: any addition to the extensions/MIME types above
			 * is not tested and may produce undesirable security holes in your
			 * web site. Please add file types keeping security in mind. I am
			 * not to be held responsible for any damage created to your site
			 * by the use of the above (or other) file types.
			 */
			return $allowed_types;
	}

	/**
	 * Returns true if the extension is allowed and the MIME type matches the extension
	 *
	 * @param string $ext file extension
	 * @return bool true on success
	 */
	function _isAllowedType($mime, $ext) {
		$allowed_types = SCUpload::allowedTypes();
		$is_in_array = array_key_exists($ext, $allowed_types);
		if ( array_key_exists($ext, $allowed_types) ) {
			if ( $mime == $allowed_types[$ext] ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		return false;
	}

	/**
	 * Checks if the file is allowed to be uploaded
	 *
	 * @param file extension $ext
	 * @since 0.7b
	 * @return true on success
	 */
	function typeCheck($ext, $area) {
		$allowed_types = SCUpload::allowedTypes();

		if ( !in_array($ext, $allowed_types[$area] ) ) {
			return false;
		} else {
			return true;
		}
	}

	function extensionFromMime($mime) {
		$allowed_types = SCUpload::allowedTypes();
		$ext = array_search($mime, $allowed_types);
		if ( $ext ) {
			return $ext;
		} else {
			return false;
		}
	}


	/**
	 * Checks an image for consistency and reduces its size if it is too big.
	 *
	 * @param file $imagefile
	 * @param string $imagepath
	 * @return true on success, false on error
	 * @since 0.7b
	 */
	function imageCheck($imagefile, $imagepath, $limit_h, $limit_w, $limit_size = 100) {
		//TODO image check function
	}


	/**
	 * Checks the GD Version if available (for on-the-fly thumbnail creation)
	 *
	 */
	function checkGdVersion() {
		//TODO GD version check function for image upload
	}


	/**
	 * Creates a thumbnail from a given image
	 *
	 * @param file $imagefile
	 * @param string $imagepath
	 * @return thumbnail file name, false on error
	 * @since 0.7b
	 */
	function createThumb($imagefile, $imagepath, $height = 40, $width = 40) {
		//TODO: thumbnail creation function
	}


	/**
	 * Saves the file to disk
	 *
	 * @param file $file
	 * @param string $path
	 * @since 0.7b
	 * @return true on success
	 */
	function save($file, $path) {
		//TODO file save function
	}
	
	
	/**
	 * Checks if the passed directory exists and, if yes, if it is writable
	 *
	 * @since 0.7.1
	 * @param string $dirname directory location
	 * @return string situation
	 */
	function checkFolder($dirname) {
		$fulldir = JPATH_ROOT . $dirname;
		$writeable		= '<b><font color="green">'. JText::_( 'writable' ) .'</font></b>';
		$unwriteable	= '<b><font color="red">'. JText::_( 'unwritable' ) .'</font></b>';
		$folder_not_exists  = '<b><font color="red">'. JText::_( 'The folder') . ' ' . $dirname . ' ' .  JText::_('does not exist' ) .'</font></b>';
				
		if ( !file_exists($fulldir) ) {
			return $folder_not_exists;	
		} else {
			$folderdesc = JText::_('Folder') . ' ' . $dirname . ' ' . JText::_('is') . ': ';
			if ( is_writable("$fulldir") ) {
				return $folderdesc . $writeable ;
			} else {
				return $folderdesc . $unwriteable ;
			}
		}
	}

	/**
	 * Returns the filename with the id of the associated event
	 *
	 * @since 0.7
	 * @param string $filename the file name
	 * @param string $dirname the directory name
	 * @param int $id the event id
	 * @return string new file name
	 */
	function getFileNameWithId($filename, $dirname = '', $id) {
		if ( $dirname == '' ) {
			return 'id' . $id . '-' . $filename;
		} else {
			return $dirname . DS . 'id' . $id . '-' . $filename;
		}
	}

	/**
	 * Function to upload a file (1st version...)
	 *
	 * @param file $file
	 * @param string $dir_upload
	 * @param integer $max size
	 * @return bool true on success
	 */
	function uploader($file, $dir_upload, $max=2097152, $id) {
		// original from: http://webcheatsheet.com/php/file_upload.php
		$string = '';
		//Check that we have a file
		if ( !empty($file) && $file['error'] == 0 ) {
			//Check if the file size is less than
			$filename = basename( $file['name'] );
			$ext = substr($filename, strrpos($filename, '.') + 1);
				
			if ( SCUpload::_isAllowedType($file['type'], $ext) && $file['size'] < $max ) {
				//Determine the path to which we want to save this file
				$oldname = JPATH_SITE . DS . $dir_upload . DS .$filename;
				//Check if the file with the same name is already exists on the server
				/* if (!file_exists($newname)) { */
				//Attempt to move the uploaded file to it's new place
				if ( move_uploaded_file($file['tmp_name'], $oldname) ) {
					$newname = SCUpload::getFileNameWithId($file['name'], JPATH_SITE.DS.$dir_upload, $id);
					if ( rename($oldname, $newname) ) {
						return true;
					} else {
						$string .= JText::_('ERROR! File already exists. Please delete it first.');
					}
				} else {
					$string .= JText::_('ERROR_IN_UPLOAD');
				}
			} else {
				$string .= JText::_('ERROR! File type not allowed!');
			}
		} else {
			$string .= JText::_('ERROR! No file uploaded!');
		}
		return $string;
	}

}
?>