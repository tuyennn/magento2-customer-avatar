<?php

namespace GhoSter\CustomerAvatar\Model;

use Magento\Catalog\Model\ImageUploader as CatalogImageUploader;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Uploader;

/**
 * Class ImageUploader model image uploader
 */
class ImageUploader extends CatalogImageUploader
{
    public const TMP_PATH_PREFIX = 'tmp';
    public const CUSTOMER_MEDIA_PATH = 'customer';
    public const CUSTOMER_MEDIA_TMP_PATH = 'customer/tmp';
    public const AVATAR_FOLDER_RESIZED = 'avatar';

    /**
     * @inheritdoc
     */
    public function moveFileFromTmp($imageName, $returnRelativePath = false): string
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $baseImagePath = $this->getBaseImagePath(
            $imageName
        );

        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        if (substr($baseImagePath, 0, strlen(self::CUSTOMER_MEDIA_PATH . DIRECTORY_SEPARATOR))
            == self::CUSTOMER_MEDIA_PATH . DIRECTORY_SEPARATOR) {
            $baseImagePath = DIRECTORY_SEPARATOR
                . str_replace(self::CUSTOMER_MEDIA_PATH . DIRECTORY_SEPARATOR, '', $baseImagePath);
        }

        return $returnRelativePath ? $baseImagePath : $imageName;
    }

    /**
     * Get base Image path
     *
     * @param string $imageName
     * @return string
     */
    public function getBaseImagePath(string $imageName): string
    {
        $basePathConf = $this->getBasePath();
        $basePath = $basePathConf . DIRECTORY_SEPARATOR
            . $imageName[0] . DIRECTORY_SEPARATOR
            . $imageName[1];

        return $this->getFilePath(
            $basePath,
            Uploader::getNewFileName(
                $this->mediaDirectory->getAbsolutePath(
                    $this->getFilePath($basePath, $imageName)
                )
            )
        );
    }

    /**
     * Save file to temp dir
     *
     * @param mixed $fileId
     * @return array|string[]
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveFileToTmpDir($fileId): array
    {
        $result = parent::saveFileToTmpDir($fileId);

        if (isset($result['file'])) {
            $tempFile = self::TMP_PATH_PREFIX . DIRECTORY_SEPARATOR . $result['file'];
            $result['url'] = $this->storeManager
                    ->getStore()
                    ->getBaseUrl() . 'avatar/file/view/image/' . base64_encode($tempFile);
        }

        return $result;
    }

    /**
     * Duplicate the file
     *
     * @param string $fileName
     *
     * @return string
     * @throws FileSystemException
     */
    public function duplicateFile(string $fileName): string
    {
        $basePath = $this->getBasePath();
        $validName = $this->getValidNewFileName($basePath, $fileName);

        $oldName = $this->getFilePath($basePath, $fileName);
        $newName = $this->getFilePath($basePath, $validName);

        $this->mediaDirectory->copyFile(
            $oldName,
            $newName
        );

        return $validName;
    }

    /**
     * Get valid new file name
     *
     * @param string $basePath
     * @param string $imageName
     *
     * @return string
     */
    private function getValidNewFileName(string $basePath, string $imageName): string
    {
        return Uploader::getNewFileName(
            $this->mediaDirectory->getAbsolutePath(
                $this->getFilePath($basePath, $imageName)
            )
        );
    }
}
