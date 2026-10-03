<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Panth\PerformanceOptimizer\Helper\Data;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @param array<string, mixed> $values
     */
    private function helper(array $values): Data
    {
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            static fn(string $path) => $values[$path] ?? null
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        return new Data($context);
    }

    public function testGetConfigValuePrefixesPathAndPassesStoreScope(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('panth_performance/general/enabled', ScopeInterface::SCOPE_STORE, 7)
            ->willReturn('1');
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        $this->assertSame('1', (new Data($context))->getConfigValue('general/enabled', 7));
    }

    public function testIsEnabledCastsConfigValue(): void
    {
        $this->assertTrue($this->helper(['panth_performance/general/enabled' => '1'])->isEnabled());
        $this->assertFalse($this->helper(['panth_performance/general/enabled' => '0'])->isEnabled());
        $this->assertFalse($this->helper([])->isEnabled());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function featureFlagProvider(): array
    {
        return [
            'defer third party' => ['isDeferThirdPartyEnabled', 'script_optimization/defer_third_party'],
            'font display swap' => ['isFontDisplaySwapEnabled', 'font_optimization/font_display_swap'],
            'x-cloak style' => ['isXCloakStyleEnabled', 'layout_stability/x_cloak_style'],
            'image dimensions' => ['isSetImageDimensionsEnabled', 'layout_stability/set_image_dimensions'],
            'iframe lazy load' => ['isIframeLazyLoadEnabled', 'iframe_lazyload/enabled'],
        ];
    }

    #[DataProvider('featureFlagProvider')]
    public function testFeatureFlagRequiresMasterSwitchAndOwnFlag(string $method, string $path): void
    {
        $both = $this->helper([
            'panth_performance/general/enabled' => '1',
            'panth_performance/' . $path => '1',
        ]);
        $masterOff = $this->helper([
            'panth_performance/general/enabled' => '0',
            'panth_performance/' . $path => '1',
        ]);
        $flagOff = $this->helper([
            'panth_performance/general/enabled' => '1',
            'panth_performance/' . $path => '0',
        ]);

        $this->assertTrue($both->$method());
        $this->assertFalse($masterOff->$method());
        $this->assertFalse($flagOff->$method());
    }

    public function testExcludedDomainsAreSplitTrimmedAndBlankLinesDropped(): void
    {
        $helper = $this->helper([
            'panth_performance/script_optimization/excluded_domains' => " cdn.example.com \n\n  js.example.org\r\n   \n",
        ]);

        $this->assertSame(
            ['cdn.example.com', 'js.example.org'],
            array_values($helper->getExcludedDomains())
        );
    }

    public function testExcludedDomainsEmptyWhenUnset(): void
    {
        $this->assertSame([], $this->helper([])->getExcludedDomains());
        $this->assertSame(
            [],
            $this->helper(['panth_performance/script_optimization/excluded_domains' => ''])->getExcludedDomains()
        );
    }
}
