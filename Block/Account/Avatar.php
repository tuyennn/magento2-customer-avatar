<?php

namespace GhoSter\CustomerAvatar\Block\Account;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Avatar extends Template
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'GhoSter_CustomerAvatar::account/header/avatar.phtml';

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @param Context $context
     * @param HttpContext $httpContext
     * @param array $data
     */
    public function __construct(
        Context $context,
        HttpContext $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
    }

    /**
     * Checking customer login status
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }
}
