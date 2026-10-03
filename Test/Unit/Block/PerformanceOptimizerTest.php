<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\Block;

use Panth\PerformanceOptimizer\Block\PerformanceOptimizer;
use Panth\PerformanceOptimizer\Helper\Data;
use PHPUnit\Framework\TestCase;

class PerformanceOptimizerTest extends TestCase
{
    private function block(Data $helper): PerformanceOptimizer
    {
        $reflection = new \ReflectionClass(PerformanceOptimizer::class);
        $block = $reflection->newInstanceWithoutConstructor();
        $property = $reflection->getProperty('performanceHelper');
        $property->setValue($block, $helper);

        return $block;
    }

    public function testExposesHelperAndEnabledFlag(): void
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturn(true);
        $block = $this->block($helper);

        $this->assertSame($helper, $block->getPerformanceHelper());
        $this->assertTrue($block->isEnabled());
    }

    public function testIsEnabledFalseWhenHelperDisabled(): void
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturn(false);

        $this->assertFalse($this->block($helper)->isEnabled());
    }

    public function testExcludedDomainsJsonIsAListWithUnescapedSlashes(): void
    {
        $helper = $this->createStub(Data::class);
        $helper->method('getExcludedDomains')->willReturn([2 => 'cdn.example.com/js', 5 => 'other.org']);

        $this->assertSame('["cdn.example.com/js","other.org"]', $this->block($helper)->getExcludedDomainsJson());
    }

    public function testExcludedDomainsJsonEscapesHtmlSensitiveCharacters(): void
    {
        $helper = $this->createStub(Data::class);
        $helper->method('getExcludedDomains')->willReturn(['</script><b>&"\'']);

        $json = $this->block($helper)->getExcludedDomainsJson();

        $this->assertStringNotContainsString('<', $json);
        $this->assertStringNotContainsString('>', $json);
        $this->assertStringNotContainsString('&', $json);
        $this->assertStringNotContainsString("'", $json);
        $this->assertSame(['</script><b>&"\''], json_decode($json, true));
    }

    public function testExcludedDomainsJsonEmptyArray(): void
    {
        $helper = $this->createStub(Data::class);
        $helper->method('getExcludedDomains')->willReturn([]);

        $this->assertSame('[]', $this->block($helper)->getExcludedDomainsJson());
    }
}
