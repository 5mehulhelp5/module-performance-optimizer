<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\Config;

use PHPUnit\Framework\TestCase;

class ModuleConfigTest extends TestCase
{
    private const ROOT = __DIR__ . '/../../../';

    private function load(string $relative): \SimpleXMLElement
    {
        $path = self::ROOT . $relative;
        $this->assertTrue(is_file($path), $relative . ' exists');
        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string((string) file_get_contents($path));
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml, $relative . ' is valid XML');
        return $xml;
    }

    public function testCloakBlockIsRenderedInsideHead(): void
    {
        $xml = $this->load('view/frontend/layout/default.xml');

        $inHead = $xml->xpath('//referenceBlock[@name="head.additional"]/block[@name="performance.optimizer"]');
        $this->assertCount(1, $inHead);
        $this->assertSame(
            'Panth_PerformanceOptimizer::performance-optimizer.phtml',
            (string) $inHead[0]['template']
        );
        $this->assertCount(0, $xml->xpath('//referenceContainer[@name="before.body.end"]'));
    }

    public function testAdminSectionShowsOnlySettingsThatChangeOutput(): void
    {
        $xml = $this->load('etc/adminhtml/system.xml');
        $section = $xml->xpath('//section[@id="panth_performance"]');
        $this->assertCount(1, $section);
        $this->assertSame('Panth_PerformanceOptimizer::config', (string) $section[0]->resource);

        $groups = [];
        foreach ($section[0]->group as $group) {
            $groups[] = (string) $group['id'];
        }
        $this->assertSame(
            ['general', 'font_optimization', 'layout_stability', 'iframe_lazyload'],
            $groups
        );
        $this->assertCount(0, $xml->xpath('//field[@id="defer_third_party" or @id="excluded_domains"]'));
    }

    public function testEveryFeatureFieldDependsOnTheMasterSwitch(): void
    {
        $xml = $this->load('etc/adminhtml/system.xml');
        $fields = $xml->xpath('//section[@id="panth_performance"]/group[@id!="general"]/field');
        $this->assertNotEmpty($fields);
        foreach ($fields as $field) {
            $depends = $field->xpath('depends/field[@id="panth_performance/general/enabled"]');
            $this->assertCount(1, $depends, (string) $field['id']);
            $this->assertSame('1', (string) $depends[0]);
        }
    }

    public function testMenuAndAclUseTheConfigResource(): void
    {
        $acl = $this->load('etc/acl.xml');
        $this->assertCount(
            1,
            $acl->xpath(
                '//resource[@id="Panth_Core::panth_extensions"]'
                . '/resource[@id="Panth_PerformanceOptimizer::performance"]'
                . '/resource[@id="Panth_PerformanceOptimizer::config"]'
            )
        );

        $menu = $this->load('etc/adminhtml/menu.xml');
        $items = $menu->xpath('//add');
        $this->assertCount(2, $items);
        foreach ($items as $item) {
            $this->assertSame('Panth_PerformanceOptimizer::config', (string) $item['resource']);
        }
        $settings = $menu->xpath('//add[@id="Panth_PerformanceOptimizer::settings"]');
        $this->assertSame(
            'adminhtml/system_config/edit/section/panth_performance',
            (string) $settings[0]['action']
        );
    }

    public function testDefaultsEnableEveryVisibleFeature(): void
    {
        $xml = $this->load('etc/config.xml');
        $defaults = $xml->default->panth_performance;

        $this->assertSame('1', (string) $defaults->general->enabled);
        $this->assertSame('1', (string) $defaults->font_optimization->font_display_swap);
        $this->assertSame('1', (string) $defaults->layout_stability->x_cloak_style);
        $this->assertSame('1', (string) $defaults->layout_stability->set_image_dimensions);
        $this->assertSame('1', (string) $defaults->iframe_lazyload->enabled);
    }
}
