<?php

namespace Core;


class FileUploader
{
    private static array $allowedImgExt = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
    private static array $allowedImgMime = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'image/svg+xml'];
    private static string $uploadedFileName;
    private static string $uploadedFileLoc;
    private static string $uploadedFileMime;
    private static int $uploadedFileSize;

    public static int $SIZE_LOW = 5000000;
    public static int $SIZE_MID = 15000000;
    public static int $SIZE_HIGH = 30000000;

    public static function extractFileData($file): void
    {
        static::$uploadedFileName = $file['name'];
        static::$uploadedFileLoc = $file['tmp_name'];
        static::$uploadedFileMime = $file['type'];
        static::$uploadedFileSize = $file['size'];
    }

    public static function getExtension()
    {
        $extensionArr = explode(".", static::$uploadedFileName);
        return strtolower(end($extensionArr));
    }

    public static function checkExtension()
    {
        $fileExtension = static::getExtension();

        if (!in_array($fileExtension, static::$allowedImgExt)) {
            return 0;
        }
        return 1;
    }

    public static function checkMime()
    {
        if (!in_array(static::$uploadedFileMime, static::$allowedImgMime)) {
            return 0;
        }
        return 1;
    }

    public static function checkSize($allowedSize = 5000000) // 5MB
    {
        if (static::$uploadedFileSize > $allowedSize) {
            return 0;
        }
        return 1;
    }

    public static function getNewFileName()
    {
        $newName = sha1_file(static::$uploadedFileLoc) . rand(1, 999999);
        $newName .= "." . static::getExtension();
        return $newName;
    }

    public static function moveFile($newName, $path)
    {
        return move_uploaded_file(static::$uploadedFileLoc, $path . $newName);
    }

    public static function upload($file, $path, $allowedSize = 5000000, $isFileRequired = true, $inputName = null, $oldFile = null)
    {
        $errors = [];
        if (!$file['name']) {
            if ($isFileRequired) return ['errors', $inputName . ' is required'];
            else return [false];
        }
        static::extractFileData($file);
        if (!static::checkExtension()) $errors[] = 'This Extension Is Not Allowed';
        if (!static::checkMime()) $errors[] = 'This Mime Is Not Allowed';
        if (!static::checkSize($allowedSize)) $errors[] = 'File Size Is Not Allowed';
        if (!empty($errors)) return ['errors', $errors];
        $newName = static::getNewFileName();
        if (static::moveFile($newName, basePath("public/assets/uploads/$path/"))) {
            if ($oldFile && $oldFile != '') self::deletePic($oldFile, $path);
            return [true, $newName];
        } else {
            $errors[] = "Failed To Upload File";
            return ['errors', $errors];
        }
    }

    public static function deletePic($imgName, $path): void
    {
        unlink(basePath("public/assets/uploads/$path/$imgName"));
    }
}