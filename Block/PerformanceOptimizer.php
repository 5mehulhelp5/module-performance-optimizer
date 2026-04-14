<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 */
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\PerformanceOptimizer\Helper\Data as PerformanceHelper;

class PerformanceOptimizer extends Template
{
    /**
     * @var PerformanceHelper
     */
    private PerformanceHelper $performanceHelper;

    /**
     * @param Context $context
     * @param PerformanceHelper $performanceHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        PerformanceHelper $performanceHelper,
        array $data = []
    ) {
        $this->performanceHelper = $performanceHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get performance helper
     *
     * @return PerformanceHelper
     */
    public function getPerformanceHelper(): PerformanceHelper
    {
        return $this->performanceHelper;
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->performanceHelper->isEnabled();
    }

    /**
     * Get excluded domains as JSON array for use in JavaScript
     *
     * @return string
     */
    public function getExcludedDomainsJson(): string
    {
        return (string) json_encode($this->performanceHelper->getExcludedDomains());
    }
}
