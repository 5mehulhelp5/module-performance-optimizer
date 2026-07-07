<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\PerformanceOptimizer\Helper\Data as PerformanceHelper;

class PerformanceOptimizer extends Template
{
    private PerformanceHelper $performanceHelper;

    public function __construct(
        Context $context,
        PerformanceHelper $performanceHelper,
        array $data = []
    ) {
        $this->performanceHelper = $performanceHelper;
        parent::__construct($context, $data);
    }

    public function getPerformanceHelper(): PerformanceHelper
    {
        return $this->performanceHelper;
    }

    public function isEnabled(): bool
    {
        return $this->performanceHelper->isEnabled();
    }

    public function getExcludedDomainsJson(): string
    {
        return (string) json_encode($this->performanceHelper->getExcludedDomains());
    }
}
