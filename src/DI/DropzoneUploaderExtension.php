<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\Form;


/**
 * Description of DropzoneUploader
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 */
class DropzoneUploaderExtension extends \Nette\DI\CompilerExtension
{
	
	public $defaults = [
		'wwwDir' => '%wwwDir%',
		'path' => 'gallery/original',
		'settings' => [],
		'photo' => [
			'width' => NULL,
			'height' => NULL,
			'flags' => \Nette\Utils\Image::FIT,
			'quality' => NULL,
			'type' => NULL
		],
		'isImage' => TRUE,
		'allowType' => NULL,
		'rewriteExistingFiles' => FALSE,
        	'generateRandomFileName' => FALSE

    	];
    
    
    	public function getDefaults()
	{
		return $this->getConfig($this->defaults);
	}
	
	
	public function loadConfiguration()
	{
		
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('dropzone'))
			->setImplement('Oli\Form\DropzoneUploaderFactory')
			->setFactory('Oli\Form\DropzoneUploader')
			->addSetup('setWwwDir', [$config['wwwDir']])
			->addSetup('setPath', [$config['path']])
			->addSetup('setSettings', [$config['settings']])
			->addSetup('setPhoto', [$config['photo']])
			->addSetup('isImage', [$config['isImage']])
			->addSetup('setAllowType', [$config['allowType']])
			->addSetup('setRewriteExistingFiles', [$config['rewriteExistingFiles']])
            		->addSetup('setGenerateRandomFileName', [$config['generateRandomFileName']]);
		
	}
	
}
