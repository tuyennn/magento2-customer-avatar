<?php

namespace GhoSter\CustomerAvatar\Helper;

use GhoSter\CustomerAvatar\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Mime as FileMime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File as DriverFile;
use Magento\Framework\Image\Adapter\AdapterInterface;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Image Processor helper
 */
class Image extends AbstractHelper
{
    /** @var DirectoryList */
    protected $directoryList;

    /** @var Filesystem\Directory\WriteInterface */
    protected $mediaWriteDirectory;

    /** @var Filesystem */
    protected $filesystem;

    /** @var AdapterFactory */
    protected $imageFactory;

    /** @var DriverFile */
    private $driverFile;

    /** @var FileMime */
    private $fileMime;

    /** @var ImageUploader */
    protected $imageUploader;

    /** @var  StoreManagerInterface */
    protected $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param DirectoryList $directoryList
     * @param Filesystem $filesystem
     * @param DriverFile $driverFile
     * @param FileMime $fileMime
     * @param AdapterFactory $imageFactory
     * @param ImageUploader $imageUploader
     * @param StoreManagerInterface $storeManager
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        DriverFile $driverFile,
        FileMime $fileMime,
        AdapterFactory $imageFactory,
        ImageUploader $imageUploader,
        StoreManagerInterface $storeManager
    ) {
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->driverFile = $driverFile;
        $this->fileMime = $fileMime;
        $this->imageFactory = $imageFactory;
        $this->mediaWriteDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageUploader = $imageUploader;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get Resize Image Url
     *
     * @param string $imageName
     * @param mixed $width
     * @param mixed $height
     * @return string
     * @throws FileSystemException|NoSuchEntityException
     */
    public function getResizedImageUrl(
        string $imageName,
        $width,
        $height = null
    ): string {
        $imageDir = $this->directoryList->getPath('media')
            . DIRECTORY_SEPARATOR . ImageUploader::CUSTOMER_MEDIA_PATH
            . $imageName;

        if (empty($height)) {
            $height = $width;
        }

        if (strpos($imageName, '/') !== false) {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $imageName = basename($imageName);
        }

        $imageResizedDir = $this->directoryList->getPath('media')
            . DIRECTORY_SEPARATOR . ImageUploader::CUSTOMER_MEDIA_PATH
            . DIRECTORY_SEPARATOR . ImageUploader::AVATAR_FOLDER_RESIZED
            . DIRECTORY_SEPARATOR . $width . 'x' . $height;

        $resizedPath =  '/' . ImageUploader::AVATAR_FOLDER_RESIZED . '/' . $width . 'x' . $height . '/' . $imageName;
        $imageResized = $imageResizedDir . DIRECTORY_SEPARATOR . $imageName;

        try {
            if (!$this->driverFile->isExists($imageResized)) {
                $this->_generateResizedImage(
                    $imageResized,
                    $imageDir,
                    $width,
                    $height
                );
            }
        } catch (\Exception $e) {
            return $this->storeManager->getStore()
                    ->getBaseUrl()
                . 'avatar/file/view/image/'
                . base64_encode($resizedPath);
        }

        return $this->storeManager->getStore()
                ->getBaseUrl()
            . 'avatar/file/view/image/'
            . base64_encode($resizedPath);
    }

    /**
     * Generate Image
     *
     * @param string $imageResizedPath
     * @param string $imageDir
     * @param mixed $width
     * @param mixed $height
     */
    private function _generateResizedImage(
        string $imageResizedPath,
        string $imageDir,
        $width,
        $height
    ) {
        try {
            /** @var $image AdapterInterface */
            $image = $this->imageFactory->create();
            $image->open($imageDir);
            $image->constrainOnly(true);
            $image->keepAspectRatio(true);
            $image->keepFrame(true);
            $image->backgroundColor([255, 255, 255]);
            $image->resize($width, $height);
            $image->save($imageResizedPath);
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
        }
    }
}
