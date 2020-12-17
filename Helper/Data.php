<?php

namespace GhoSter\CustomerAvatar\Helper;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\MediaStorage\Helper\File\Storage;

/**
 * Class Data as main helper
 */
class Data extends AbstractHelper
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Storage
     */
    protected $coreFileStorage;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var AssetRepository
     */
    protected $assetRepo;

    /**
     * Data constructor.
     * @param Context $context
     * @param Filesystem $fileSystem
     * @param Storage $coreFileStorage
     * @param CustomerFactory $customerFactory
     * @param AssetRepository $assetRepo
     */
    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        Storage $coreFileStorage,
        CustomerFactory $customerFactory,
        AssetRepository $assetRepo
    ) {
        parent::__construct($context);
        $this->fileSystem = $fileSystem;
        $this->coreFileStorage = $coreFileStorage;
        $this->customerFactory = $customerFactory;
        $this->assetRepo = $assetRepo;
    }

    /**
     * @return string
     */
    public function getPlaceHolderAvatar(): string
    {
        return $this->assetRepo->getUrl('GhoSter_CustomerAvatar::images/no-photo.png');
    }
}
