<?php

namespace GhoSter\CustomerAvatar\Block\Form;

use GhoSter\CustomerAvatar\Helper\Data as CustomerAvatarHelper;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Form\Edit as CustomerFormEdit;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;

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
     * Edit constructor.
     * @param CustomerAvatarHelper $customerAvatarHelper
     * @param Context $context
     * @param Session $customerSession
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        CustomerAvatarHelper $customerAvatarHelper,
        Context $context,
        Session $customerSession,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->customerAvatarHelper = $customerAvatarHelper;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): string
    {
        $customer = $this->getCustomer();

        if (!$customer->getId()) {
            return $this->customerAvatarHelper->getPlaceHolderAvatar();
        }

        if (!($customer->getCustomAttribute('avatar'))) {
            return $this->customerAvatarHelper->getPlaceHolderAvatar();
        }

        return $this->getUrl(
            'avatar/file/view/',
            ['image' => base64_encode($customer->getCustomAttribute('avatar')->getValue())]
        );
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
