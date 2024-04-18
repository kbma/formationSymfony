<?php

namespace App\Service;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class UploaderService
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function uploadFile(UploadedFile $file,string $directoryFolder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move(
                $directoryFolder,
                $newFilename
            );
        } catch (FileException $e) {
        }
        return $newFilename;
    }
}