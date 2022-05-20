<?php

namespace GhoSter\CustomerAvatar\Block\Form;

use GhoSter\CustomerAvatar\Helper\Data as CustomerAvatarHelper;
use Magento\Customer\Block\Form\Edit as CustomerFormEdit;
use Magento\Framework\App\ObjectManager;

/**
 * Class Edit for block additional data
 */
class Edit extends CustomerFormEdit
{
    /**
     * @var CustomerAvatarHelper
     */
    protected $customerAvatarHelper;

    /**
     * @return string
     */
    public function getAvatarUrl(): string
    {
        $customer = $this->getCustomer();

        if (!$customer->getId()) {
            return $this->getAvatarHelper()->getPlaceHolderAvatar();
        }

        if (!($customer->getCustomAttribute('avatar'))) {
            return $this->getAvatarHelper()->getPlaceHolderAvatar();
        }

        return $this->getUrl(
            'avatar/file/view/',
            ['image' => base64_encode($customer->getCustomAttribute('avatar')->getValue())]
        );
    }

    /**
     * Get customer avatar helper
     *
     * @return CustomerAvatarHelper|mixed
     */
    public function getAvatarHelper()
    {
        if ($this->customerAvatarHelper === null) {
            $this->customerAvatarHelper = ObjectManager::getInstance()->get(CustomerAvatarHelper::class);
        }

        return $this->customerAvatarHelper;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        $customer = $this->getCustomer();

        return $customer->getCustomAttribute('avatar')
            ? $customer->getCustomAttribute('avatar')->getValue()
            : '';
    }
}
