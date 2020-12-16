<?php

namespace GhoSter\CustomerAvatar\Controller\File;

use GhoSter\CustomerAvatar\Model\ImageUploader;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class AbstractUpload abstract upload action
 */
abstract class AbstractUpload extends Action
{
    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * AbstractUpload constructor.
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param PageFactory $resultPageFactory
     * @param Data $jsonHelper
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader,
        PageFactory $resultPageFactory,
        Data $jsonHelper
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * @return string
     */
    abstract protected function getFileId();

    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir($this->getFileId());
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
