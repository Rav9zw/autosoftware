<?php

namespace App\Service;

use App\Request\MessageListRequest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class Messages
{

    const FILE_NAME_SUFFIX = '_message.json';

    public string $fileDirectory;
    private Filesystem $filesystem;
    private ValidatorInterface $validator;
    private MessageListRequest $messageListRequest;


    public function __construct(Filesystem $filesystem, ValidatorInterface $validator, MessageListRequest $messageListRequest)
    {
        $this->fileDirectory=dirname(__DIR__, 2)."/storage/";
        $this->filesystem = $filesystem;
        $this->validator = $validator;
        $this->messageListRequest = $messageListRequest;
    }

    public function createNew($content): ?string
    {
        if ($content && array_key_exists('message', $content)) {
            $content = $content['message'];
        } else {
            $content = null;
        }

        $uuid = uuid_create(UUID_TYPE_RANDOM);

        $message = [
            'uuid' => $uuid,
            'content' => $content,
            'createdAt' => date('Y-m-d H:i:s')
        ];

        $this->filesystem->dumpFile($this->fileDirectory . $uuid . self::FILE_NAME_SUFFIX, json_encode($message));
        return $uuid;
    }

    public function findByUuid($uuid)
    {
        $filePath = $this->fileDirectory . $uuid .self:: FILE_NAME_SUFFIX;

        if ($this->filesystem->exists($filePath)) {
            $fileContents = file_get_contents($filePath);
            return json_decode($fileContents);
        } else {
            return 'no data for provided uuid';
        }

    }

    public function list($sortBy, $orderBy): array
    {
        $finder = new Finder();
        $finder->files()->in($this->fileDirectory);


        if ($sortBy == 'date') {
            $finder->sortByModifiedTime();
        } elseif ($sortBy == 'uuid') {
            $finder->sortByName();
        }
        if ($orderBy == 'desc') {
            $finder->reverseSorting();
        }

        $fileList = [];

        $i = 0;
        foreach ($finder as $file) {
            $content = json_decode($file->getContents());
            $fileList[$i]['name'] = $file->getRelativePathname();
            $fileList[$i]['created_at'] = $content->createdAt;
            $i++;
        }

        return $fileList;

    }

    public function validateRequestForList($sortBy, $orderBy): bool|string
    {

        $this->messageListRequest->sortBy = $sortBy;
        $this->messageListRequest->orderBy = $orderBy;

        $errors = $this->validator->validate($this->messageListRequest);


        if (count($errors) > 0) {
            return (string)$errors;
        } else {
            return false;
        }

    }
}