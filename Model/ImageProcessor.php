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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var AdapterFactory
     */
    protected $imageFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ImageProcessor constructor.
     * @param Filesystem $filesystem
     * @param ImageUploader $imageUploader
     * @param StoreManagerInterface $storeManager
     * @param AdapterFactory $imageFactory
     */
    public function __construct(
        Filesystem $filesystem,
        ImageUploader $imageUploader,
        StoreManagerInterface $storeManager,
        AdapterFactory $imageFactory
    ) {
        $this->filesystem = $filesystem;
        $this->imageUploader = $imageUploader;
        $this->storeManager = $storeManager;
        $this->imageFactory = $imageFactory;
    }

    /**
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
