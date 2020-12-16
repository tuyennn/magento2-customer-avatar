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
    const XML_PATH_ENABLED_HEADER = 'avatar/general/enabled_header';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var StoreManagerInterface */
    protected $storeManager;


    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
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
