/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
   config.filebrowserBrowseUrl = '../required/ckfinder.html';
   config.filebrowserImageBrowseUrl = '../required/ckfinder/ckfinder.html?type=Images';
   config.filebrowserFlashBrowseUrl = '../required/ckfinder/ckfinder.html?type=Flash';
   config.filebrowserUploadUrl = '../required/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
   config.filebrowserImageUploadUrl = '../required/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
   /*config.filebrowserFlashUploadUrl = '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';*/
};