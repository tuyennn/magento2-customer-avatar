<?php

namespace GhoSter\CustomerAvatar\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * CustomerAvatar main Config
 */
class Config
{
    protected const XML_PATH_ENABLED_HEADER = 'avatar/general/enabled_header';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Determine header display enabled
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isEnabledHeader($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_ENABLED_HEADER,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
