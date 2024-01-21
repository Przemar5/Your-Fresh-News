<?php

namespace App\Handlers;

use Illuminate\Http\UploadedFile;

class FileHandler 
{
	protected $file;

	// Storage directory
	protected $path;

	protected $filename;


	public function __construct(\File $file)
	{
		$this->file = $file;
	}

	/**
	 * Generate filename by unix timestamp
	 *
	 * @param ?string $postfix
	 * @return void
	 */
	public function generateUniqueFilename(?string $postfix = '')
	{
		$this->filename = time() . $postfix . '.' . 
				$this->file->getClientOriginalExtension();

		return $this;
	}

	public function setFile(string $path)
	{
		$this->file = $file;

		return $this;
	}

	public function setFilename(string $filename)
	{
		$this->filename = $filename;

		return $this;
	}

	public function setPath(string $path)
	{
		$this->path = $path;

		return $this;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function getFilename()
	{
		return $this->filename;
	}

	public function getPath()
	{
		return $this->path;
	}

	/**
     * Store uploaded file.
     *
     * @param string $storageDriver
     * @param File $file
     * @return string $storedFileName
     */
    public function store(string $storageDriver)
    {
        if (isset($this->pathToFile) && isset($this->filename)) {
        	$filenameWithPath = $this->pathToFile . $this->filename;

        	return Storage::disk($storageDriver)
        			->put($filenameWithPath, file_get_contents($file));
        
        } else {
        	return false;
        }
    }

    public function storeFile(UploadedFile $file, string $path)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $fullFilename = $path . $filename;

        if (\Storage::disk('assets')->put($fullFilename, file_get_contents($file))) {
            return $filename;
        }
        
        throw new Exception("File wasn't added.");
    }
}