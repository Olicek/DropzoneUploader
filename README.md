# DropzoneUploader

Instalace
============

	composer require olicek/dropzone-uploader:dev-master

Registrovat v extensions

```
extensions:
    map: Oli\Form\DropzoneUploaderExtension 
```
    	
A nakonec nalinkovat `client-site/dropzoneUploader.js` do stránky.

Použití je potom poměrně triviální

```
public function createComponentUploader($name, DropzoneUploaderFactory $factory)
	{
		$dropzone = $factory->create();
		$dropzone->setGenerateRandomFileName(TRUE); //for random file name
		$path = $dropzone->getPath();
		$dropzone->onSuccess[] = function (DropzoneUploader $dropzoneUploader, $targetPath, $name, $suffix) {
			$photo = new Photo;
			$photo->filename = $name . '.' . $suffix;
			$photo->suffix = $suffix;

			$this->photosRepository->save($photo);
		};
		return $dropzone->setPath($path . '/' . $this->galleryEntity->folder . '/1600x1200');
	}
```

V šabloně

```
{control uploader}
```

A v základu by to mělo být vše. Ten soubor `client-site/dropzoneUploader.js` jsem se snažil udělat co nejobecnější aby bylo možné s ním pracovat pokudmožno bez zásahu do něj. Nemusí se použít vubec, jediné na čem záleží je třída `.dropzone`, která se teda taky ale může změnit :-)
