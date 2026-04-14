<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 */
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var string
     */
    private const XML_PATH_PERFORMANCE = 'panth_performance/';

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $path
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue(string $path, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PERFORMANCE . $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    // ===========================================
    // General Settings
    // ===========================================

    /**
     * Check if performance optimizer is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/enabled');
    }

    // ===========================================
    // Script Optimization
    // ===========================================

    /**
     * Check if defer third-party scripts is enabled
     *
     * @return bool
     */
    public function isDeferThirdPartyEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('script_optimization/defer_third_party');
    }

    /**
     * Get excluded domains (not to be deferred)
     *
     * @return array
     */
    public function getExcludedDomains(): array
    {
        $domains = $this->getConfigValue('script_optimization/excluded_domains');
        return $domains ? array_filter(array_map('trim', explode("\n", (string) $domains))) : [];
    }

    // ===========================================
    // Font Optimization
    // ===========================================

    /**
     * Check if font-display swap is enabled
     *
     * @return bool
     */
    public function isFontDisplaySwapEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('font_optimization/font_display_swap');
    }

    // ===========================================
    // Layout Stability
    // ===========================================

    /**
     * Check if x-cloak style injection is enabled
     *
     * @return bool
     */
    public function isXCloakStyleEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('layout_stability/x_cloak_style');
    }

    /**
     * Check if set image dimensions is enabled
     *
     * @return bool
     */
    public function isSetImageDimensionsEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('layout_stability/set_image_dimensions');
    }

    // ===========================================
    // Iframe Lazy Loading
    // ===========================================

    /**
     * Check if iframe lazy loading is enabled
     *
     * @return bool
     */
    public function isIframeLazyLoadEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->getConfigValue('iframe_lazyload/enabled');
    }
}
