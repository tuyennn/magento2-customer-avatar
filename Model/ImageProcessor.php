<?php

namespace GhoSter\CustomerAvatar\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ImageProcessor processing the images
 */
class ImageProcessor
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var AdapterFactory
     */
    protected $imageFactory;

    /**
     * ImageProcessor constructor.
     *
     * @param ImageUploader $imageUploader
     * @param AdapterFactory $imageFactory
     */
    public function __construct(
        ImageUploader $imageUploader,
        AdapterFactory $imageFactory
    ) {
        $this->imageUploader = $imageUploader;
        $this->imageFactory = $imageFactory;
    }

    /**
     * Move file from temp
     *
     * @param string $imageName
     * @param bool $returnRelativePath
     * @return string
     */
    public function moveFileFromTmp(string $imageName, $returnRelativePath = false): string
    {
        try {
            return $this->imageUploader->moveFileFromTmp($imageName, $returnRelativePath);
        } catch (LocalizedException $exception) {
            // file already was moved from tmp
            return $imageName;
        }
    }

    /**
     * Copy, duplicate the file
     *
     * @param string $imageName
     *
     * @return string
     */
    public function copyFile(string $imageName): string
    {
        try {
            return $this->imageUploader->duplicateFile($imageName);
        } catch (LocalizedException $exception) {
            // file already was duplicated
            return $imageName;
        }
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath(string $path, string $imageName): string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
