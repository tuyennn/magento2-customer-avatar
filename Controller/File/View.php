<?php

namespace GhoSter\CustomerAvatar\Controller\File;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filesystem;
use Magento\Framework\Url\DecoderInterface;
use Magento\MediaStorage\Helper\File\Storage as FileStorageHelper;
use Magento\Framework\Controller\Result\Raw as RawResult;

/**
 * Class View action view the image
 */
class View extends Action
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var DecoderInterface
     */
    protected $urlDecoder;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var FileStorageHelper
     */
    protected $fileStorageHelper;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * View constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param FileStorageHelper $fileStorageHelper
     * @param RawFactory $resultRawFactory
     * @param DecoderInterface $urlDecoder
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        FileStorageHelper $fileStorageHelper,
        RawFactory $resultRawFactory,
        DecoderInterface $urlDecoder,
        FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->fileStorageHelper = $fileStorageHelper;
        $this->resultRawFactory = $resultRawFactory;
        $this->urlDecoder = $urlDecoder;
        $this->fileFactory = $fileFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        [$file, $plain] = $this->getFileParams();

        $directory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $fileName = CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER . '/' . ltrim($file, '/');
        $path = $directory->getAbsolutePath($fileName);
        if (mb_strpos($path, '..') !== false || (!$directory->isFile($fileName)
                && !$this->fileStorageHelper->processStorageFile($path))
        ) {
            throw new NotFoundException(__('Page not found.'));
        }

        if ($plain) {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            switch (strtolower($extension)) {
                case 'gif':
                    $contentType = 'image/gif';
                    break;
                case 'jpg':
                    $contentType = 'image/jpeg';
                    break;
                case 'png':
                    $contentType = 'image/png';
                    break;
                default:
                    $contentType = 'application/octet-stream';
                    break;
            }
            $stat = $directory->stat($fileName);
            $contentLength = $stat['size'];
            $contentModify = $stat['mtime'];

            /** @var RawResult $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setHttpResponseCode(200)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Content-type', $contentType, true)
                ->setHeader('Content-Length', $contentLength)
                ->setHeader('Last-Modified', date('r', $contentModify));
            $resultRaw->setContents($directory->readFile($fileName));
            return $resultRaw;
        } else {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $name = pathinfo($path, PATHINFO_BASENAME);
            $this->fileFactory->create(
                $name,
                ['type' => 'filename', 'value' => $fileName],
                DirectoryList::MEDIA
            );
        }
    }

    /**
     * Get parameters from request.
     *
     * @return array
     * @throws NotFoundException
     */
    private function getFileParams()
    {
        $file = null;
        $plain = false;
        if ($this->getRequest()->getParam('file')) {
            $file = $this->urlDecoder->decode(
                $this->getRequest()->getParam('file')
            );
        } elseif ($this->getRequest()->getParam('image')) {
            $file = $this->urlDecoder->decode(
                $this->getRequest()->getParam('image')
            );
            $plain = true;
        } else {
            throw new NotFoundException(__('Page not found.'));
        }

        return [$file, $plain];
    }
}
