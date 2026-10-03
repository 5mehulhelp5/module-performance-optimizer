<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\View;

use Panth\PerformanceOptimizer\Block\PerformanceOptimizer;
use Panth\PerformanceOptimizer\Helper\Data;
use PHPUnit\Framework\TestCase;

class PerformanceOptimizerTemplateTest extends TestCase
{
    private const TEMPLATE = __DIR__ . '/../../../view/frontend/templates/performance-optimizer.phtml';

    private function render(bool $enabled, bool $xCloak): string
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturn($enabled);
        $helper->method('isXCloakStyleEnabled')->willReturn($enabled && $xCloak);
        $block = $this->createStub(PerformanceOptimizer::class);
        $block->method('getPerformanceHelper')->willReturn($helper);

        ob_start();
        try {
            (static function (string $file, PerformanceOptimizer $block): void {
                include $file;
            })(self::TEMPLATE, $block);
        } finally {
            $html = (string) ob_get_clean();
        }
        return $html;
    }

    public function testOutputsCloakStyleWhenEnabled(): void
    {
        $html = $this->render(true, true);

        $this->assertStringContainsString('<style>[x-cloak]{display:none!important}</style>', $html);
        $this->assertSame(1, substr_count($html, '<style>'));
        $this->assertStringNotContainsString('<script', $html);
    }

    public function testOutputsNoStyleWhenCloakStyleIsOff(): void
    {
        $html = $this->render(true, false);

        $this->assertStringNotContainsString('x-cloak', $html);
        $this->assertStringNotContainsString('<style', $html);
    }

    public function testOutputsNothingWhenModuleIsDisabled(): void
    {
        $this->assertSame('', trim($this->render(false, true)));
    }
}
