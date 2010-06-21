<?php
// vim: foldmethod=marker
/**
 *  Sampleshop_ActionForm.php
 *
 *  @author	 {$author}
 *  @package	Sampleshop
 *  @version	$Id$
 */

// {{{ Sampleshop_ActionForm
/**
 *  ActionForm class.
 *
 *  @author	 {$author}
 *  @package	Sampleshop
 *  @access	 public
 */
class Sampleshop_ActionForm extends Ethna_ActionForm
{
	/**#@+
	 *  @access private
	 */

	/** @var	array   form definition (default) */
	var $form_template = array();

	/**#@-*/

	/**
	 *  Error handling of form input validation.
	 *
	 *  @access public
	 *  @param  string	  $name   form item name.
	 *  @param  int		 $code   error code.
	 */
	function handleError($name, $code)
	{
		return parent::handleError($name, $code);
	}

	/**
	 *  setter method for form template.
	 *
	 *  @access protected
	 *  @param  array   $form_template  form template
	 *  @return array   form template after setting.
	 */
	function _setFormTemplate($form_template)
	{
		return parent::_setFormTemplate($form_template);
	}

	/**
	 *  setter method for form definition.
	 *
	 *  @access protected
	 */
	function _setFormDef()
	{
		return parent::_setFormDef();
	}

}
// }}}

?>
