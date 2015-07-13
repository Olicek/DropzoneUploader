<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\Form;


/**
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 */
interface DropzoneUploaderFactory
{
	
	/**
	 * @return DropzoneUploader
	 */
	function create();
	
}
