<?php

namespace GhoSter\CustomerAvatar\CustomerData;

use GhoSter\CustomerAvatar\Helper\Image as ImageHelper;
use GhoSter\CustomerAvatar\Model\Config;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Layout;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Avatar as Customer Private Data
 */
class Avatar implements SectionSourceInterface
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var Layout
     */
    protected $layout;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Avatar constructor.
     * @param Session $customerSession
     * @param ImageHelper $imageHelper
     * @param Config $config
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Session $customerSession,
        ImageHelper $imageHelper,
        Config $config,
        LayoutFactory $layoutFactory
    ) {
        $this->customerSession = $customerSession;
        $this->imageHelper = $imageHelper;
        $this->config = $config;
        $this->layout = $layoutFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $customer = $this->customerSession->getCustomer();

        if (!$this->config->isEnabledHeader($customer->getStoreId())) {
            return [
                'available' => false
            ];
        }

        $avatar = $customer->getData('avatar');

        if (!empty($avatar)
            && $this->config->isEnabledHeader($customer->getStoreId())
        ) {
            return [
                'available' => true,
                'url' => $this->imageHelper->getResizedImageUrl($avatar, 32)
            ];
        }

        return [
            'available' => false
        ];
    }
}
