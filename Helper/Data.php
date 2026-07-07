<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_PERFORMANCE = 'panth_performance/';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function getConfigValue(string $path, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PERFORMANCE . $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/enabled');
    }

    public function isDeferThirdPartyEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('script_optimization/defer_third_party');
    }

    public function getExcludedDomains(): array
    {
        $domains = $this->getConfigValue('script_optimization/excluded_domains');
        return $domains ? array_filter(array_map('trim', explode("\n", (string) $domains))) : [];
    }

    public function isFontDisplaySwapEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('font_optimization/font_display_swap');
    }

    public function isXCloakStyleEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('layout_stability/x_cloak_style');
    }

    public function isSetImageDimensionsEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('layout_stability/set_image_dimensions');
    }

    public function isIframeLazyLoadEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('iframe_lazyload/enabled');
    }
}
