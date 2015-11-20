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
class DropzoneUploader extends \Nette\Application\UI\Control
{
	
	private $wwwDir;
	
	private $path;

	private $settings;
	
	private $photo;
	
	private $isImage = TRUE;
	
	private $allowType = NULL;
	
	private $rewriteExistingFiles = FALSE;
	
	public $onSuccess = [];
	
	
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}


	public function getPath()
	{
		return $this->path;
	}

	
	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}
	
	
	public function setSettings(array $settings)
	{
		$this->settings = $settings;
		return $this;
	}
	
	
	public function isImage($isImage = TRUE)
	{
		$this->isImage = $isImage;
		return $this;
	}
	
	
	public function setWwwDir($wwwDir)
	{
		$this->wwwDir = $wwwDir;
		return $this;
	}


	public function setAllowType($allowType)
	{
		$this->allowType = $allowType;
		return $this;
	}


	public function setRewriteExistingFiles($rewriteExistingFiles)
	{
		$this->rewriteExistingFiles = $rewriteExistingFiles;
		return $this;
	}

	
	public function createComponentUploadForm()
	{
		$form = new \Nette\Application\UI\Form();
		
		$form->getElementPrototype()->addAttributes(["class" => "dropzone"]);
		
		$form->addUpload("file", NULL)
			->setHtmlId("fileUpload");
		
		$form->onSuccess[] = $this->process;
		
		return $form;
	}
	
	
	public function render()
	{
		$settings = $this->settings;
		$settings['onSuccess'] = $this->link($settings['onSuccess']);
		$settings['onUploadStart'] = $this->link('checkDirectory!');
		$this->template->uploadSettings = \Nette\Utils\Json::encode($settings);
		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}
	
	
	public function process(\Nette\Application\UI\Form $form, $values)
	{
		$file = $values->file;
		if(!$file instanceof \Nette\Http\FileUpload)
		{
			throw new \Nette\FileNotFoundException('Nahraný soubor není typu Nette\Http\FileUpload. Pravděpodobně se nenahrál v pořádku.');
		}
		
		if(!$file->isOk())
		{
			throw new \Nette\FileNotFoundException('Soubor byl poškozen:' . $file->error);
		}
		
		if($this->isImage && $file->isImage() !== $this->isImage)
		{
			throw new \Nette\InvalidArgumentException('Soubor musí být obrázek');
		}
		
		if(is_array($this->allowType) && in_array($file->getContentType(), $this->allowType, TRUE))
		{
			throw new \Nette\InvalidArgumentException('Soubor není povoleného typu');
		}
		
		$this->handleCheckDirectory();
		
		$targetPath = $this->wwwDir . DIRECTORY_SEPARATOR . $this->path;
		
		if($this->rewriteExistingFiles)
		{
			$name = $file->getSanitizedName();
			
		} else
		{
			$SplitedName = \Nette\Utils\Strings::split($file->getSanitizedName(), '~\.\s*~');
			$suffix = array_pop($SplitedName);
			$counter = NULL;
			
			while(is_file($targetPath . DIRECTORY_SEPARATOR .
				implode('.', $SplitedName) . $counter . '.' . $suffix))
			{
				$counter++;
			}
			
			$name = implode('.', $SplitedName) . $counter . '.' . $suffix;
			
		}
		
		if($file->isImage())
		{
			$image = $file->toImage();
			
			$width = $this->photo['width'];
			$height = $this->photo['height'];
			$flags = $this->photo['flags'];

			if(!is_null($width) || !is_null($height))
			{
				$image->resize($width, $height, $flags);
			}

			$image->save($targetPath . DIRECTORY_SEPARATOR .
				$name, $this->photo['quality'], $this->photo['type']);
			
		} else
		{
			$this->moveUploadedFile($file, $targetPath, $name);
		}

		$this->onSuccess($this, $this->path, implode('.', $SplitedName) . $counter, $suffix);
	}
	
	
	private function moveUploadedFile($file, $targetPath, $name)
	{
		$file->move($targetPath . DIRECTORY_SEPARATOR . $name);
	}
	
	
	public function handleCheckDirectory()
	{
		$oldmask = umask(0);
		
		if(!is_dir($this->wwwDir . DIRECTORY_SEPARATOR . $this->path))
		{
			mkdir($this->wwwDir . DIRECTORY_SEPARATOR . $this->path, 0777, true);
		}
		
		umask($oldmask);
	}


	public function handleRefresh(){
		$this->redrawControl('photos');
	}
	
}
