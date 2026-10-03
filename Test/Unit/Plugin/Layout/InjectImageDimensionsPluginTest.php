<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\Plugin\Layout;

use Magento\Framework\View\LayoutInterface;
use Panth\PerformanceOptimizer\Helper\Data;
use Panth\PerformanceOptimizer\Plugin\Layout\InjectImageDimensionsPlugin;
use Panth\PerformanceOptimizer\Service\ImageDimensionRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InjectImageDimensionsPluginTest extends TestCase
{
    /** @var string[] */
    private array $requested = [];

    /**
     * @param array<string, array{width: int, height: int}|null> $dimensions
     */
    private function plugin(
        bool $images = true,
        bool $iframes = true,
        bool $fonts = true,
        array $dimensions = []
    ): InjectImageDimensionsPlugin {
        $helper = $this->createStub(Data::class);
        $helper->method('isSetImageDimensionsEnabled')->willReturn($images);
        $helper->method('isIframeLazyLoadEnabled')->willReturn($iframes);
        $helper->method('isFontDisplaySwapEnabled')->willReturn($fonts);

        $registry = $this->createStub(ImageDimensionRegistry::class);
        $registry->method('getDimensions')->willReturnCallback(
            function (string $src) use ($dimensions): ?array {
                $this->requested[] = $src;
                return $dimensions[$src] ?? null;
            }
        );

        return new InjectImageDimensionsPlugin($helper, $registry);
    }

    private function process(InjectImageDimensionsPlugin $plugin, mixed $html): mixed
    {
        return $plugin->afterGetOutput($this->createStub(LayoutInterface::class), $html);
    }

    /**
     * @return array<string, array{width: int, height: int}>
     */
    private function dims(int $width = 800, int $height = 600): array
    {
        return ['/media/a.png' => ['width' => $width, 'height' => $height]];
    }

    public function testNonStringAndEmptyResultsPassThrough(): void
    {
        $plugin = $this->plugin();

        $this->assertNull($this->process($plugin, null));
        $this->assertSame('', $this->process($plugin, ''));
        $this->assertSame(42, $this->process($plugin, 42));
    }

    public function testOutputUnchangedWhenAllFeaturesDisabled(): void
    {
        $html = '<img src="/media/a.png"><iframe src="/x"></iframe><style>@font-face{src:url(a)}</style>';

        $this->assertSame($html, $this->process($this->plugin(false, false, false, $this->dims()), $html));
        $this->assertSame([], $this->requested);
    }

    public function testOutputUnchangedWhenNoRelevantMarkupPresent(): void
    {
        $html = '<div><p>Hello &lt;img&gt; world</p></div>';

        $this->assertSame($html, $this->process($this->plugin(true, true, true, $this->dims()), $html));
    }

    public function testInjectsBothDimensionsWhenMissing(): void
    {
        $out = $this->process(
            $this->plugin(dimensions: $this->dims()),
            '<p>x</p><img src="/media/a.png" alt="A"><p>y</p>'
        );

        $this->assertSame('<p>x</p><img width="800" height="600" src="/media/a.png" alt="A"><p>y</p>', $out);
    }

    public function testLeavesImageWithBothDimensionsAlone(): void
    {
        $html = '<img src="/media/a.png" width="10" height="20">';

        $this->assertSame($html, $this->process($this->plugin(dimensions: $this->dims()), $html));
        $this->assertSame([], $this->requested);
    }

    public function testComputesHeightFromExistingWidthKeepingAspectRatio(): void
    {
        $out = $this->process($this->plugin(dimensions: $this->dims()), '<img src="/media/a.png" width="400">');

        $this->assertSame('<img height="300" src="/media/a.png" width="400">', $out);
    }

    public function testComputesWidthFromExistingHeightKeepingAspectRatio(): void
    {
        $out = $this->process($this->plugin(dimensions: $this->dims()), "<img src='/media/a.png' height='150'>");

        $this->assertSame("<img width=\"200\" src='/media/a.png' height='150'>", $out);
    }

    public function testComputedDimensionIsAtLeastOne(): void
    {
        $out = $this->process(
            $this->plugin(dimensions: $this->dims(1000, 1)),
            '<img src="/media/a.png" width="10">'
        );

        $this->assertSame('<img height="1" src="/media/a.png" width="10">', $out);
    }

    public function testUnquotedAttributesAreParsed(): void
    {
        $out = $this->process($this->plugin(dimensions: $this->dims()), '<img src=/media/a.png width=80>');

        $this->assertSame('<img height="60" src=/media/a.png width=80>', $out);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function unusableSizeProvider(): array
    {
        return [
            'percent width' => ['<img src="/media/a.png" width="100%">'],
            'zero width' => ['<img src="/media/a.png" width="0">'],
            'boolean width' => ['<img src="/media/a.png" width>'],
            'css height' => ['<img src="/media/a.png" height="10px">'],
            'zero height' => ['<img src="/media/a.png" height="0">'],
        ];
    }

    #[DataProvider('unusableSizeProvider')]
    public function testNonNumericOrZeroExistingSizeLeavesTagUntouched(string $html): void
    {
        $this->assertSame($html, $this->process($this->plugin(dimensions: $this->dims()), $html));
    }

    public function testImageWithoutSrcOrUnknownDimensionsIsUntouched(): void
    {
        $plugin = $this->plugin(dimensions: $this->dims());
        $html = '<img alt="none"><img src=""><img src="/media/unknown.png">';

        $this->assertSame($html, $this->process($plugin, $html));
        $this->assertSame(['/media/unknown.png'], $this->requested);
    }

    public function testZeroDimensionsFromRegistryAreIgnored(): void
    {
        $html = '<img src="/media/a.png">';

        $this->assertSame($html, $this->process($this->plugin(dimensions: $this->dims(0, 50)), $html));
    }

    public function testSrcEntitiesAreDecodedBeforeLookup(): void
    {
        $this->process($this->plugin(), '<img src=" /media/a.png?x=1&amp;y=2 ">');

        $this->assertSame(['/media/a.png?x=1&y=2'], $this->requested);
    }

    public function testDataPrefixedAttributesDoNotCountAsDimensions(): void
    {
        $out = $this->process(
            $this->plugin(dimensions: $this->dims()),
            '<img data-width="5" data-height="5" src="/media/a.png">'
        );

        $this->assertSame(
            '<img width="800" height="600" data-width="5" data-height="5" src="/media/a.png">',
            $out
        );
    }

    public function testFirstDuplicateAttributeWins(): void
    {
        $this->process($this->plugin(), '<img src="/media/first.png" src="/media/second.png">');

        $this->assertSame(['/media/first.png'], $this->requested);
    }

    public function testQuotedGreaterThanInsideAttributeDoesNotEndTag(): void
    {
        $out = $this->process($this->plugin(dimensions: $this->dims()), '<img alt="a > b" src="/media/a.png">');

        $this->assertSame('<img width="800" height="600" alt="a > b" src="/media/a.png">', $out);
    }

    public function testUppercaseTagNameIsHandled(): void
    {
        $out = $this->process($this->plugin(dimensions: $this->dims()), '<IMG SRC="/media/a.png">');

        $this->assertSame('<IMG width="800" height="600" SRC="/media/a.png">', $out);
    }

    public function testImagesInsideRawTextElementsAndCommentsAreSkipped(): void
    {
        $html = '<!-- <img src="/media/a.png"> -->'
            . '<script>var s = \'<img src="/media/a.png">\';</script>'
            . '<textarea><img src="/media/a.png"></textarea>'
            . '<noscript><img src="/media/a.png"></noscript>';

        $this->assertSame($html, $this->process($this->plugin(dimensions: $this->dims()), $html));
        $this->assertSame([], $this->requested);
    }

    public function testNestedTemplatesAreSkippedUntilOutermostClose(): void
    {
        $html = '<template><template><img src="/media/a.png"></template><img src="/media/a.png"></template>'
            . '<img src="/media/a.png">';

        $out = $this->process($this->plugin(dimensions: $this->dims()), $html);

        $this->assertSame(
            '<template><template><img src="/media/a.png"></template><img src="/media/a.png"></template>'
            . '<img width="800" height="600" src="/media/a.png">',
            $out
        );
    }

    public function testUnclosedScriptSwallowsRestOfDocument(): void
    {
        $html = '<script>var a = 1; <img src="/media/a.png">';

        $this->assertSame($html, $this->process($this->plugin(dimensions: $this->dims()), $html));
    }

    public function testUnterminatedCommentIsCopiedVerbatim(): void
    {
        $html = '<img src="/media/a.png"><!-- open <img src="/media/a.png">';

        $this->assertSame(
            '<img width="800" height="600" src="/media/a.png"><!-- open <img src="/media/a.png">',
            $this->process($this->plugin(dimensions: $this->dims()), $html)
        );
    }

    public function testStrayLessThanCharactersArePreserved(): void
    {
        $html = '<p>1 < 2 and <3 and <</p><img src="/media/a.png"><';

        $this->assertSame(
            '<p>1 < 2 and <3 and <</p><img width="800" height="600" src="/media/a.png"><',
            $this->process($this->plugin(dimensions: $this->dims()), $html)
        );
    }

    public function testImagesUntouchedWhenOnlyIframeFeatureEnabled(): void
    {
        $out = $this->process(
            $this->plugin(false, true, false, $this->dims()),
            '<img src="/media/a.png"><iframe src="/v"></iframe>'
        );

        $this->assertSame('<img src="/media/a.png"><iframe loading="lazy" src="/v"></iframe>', $out);
        $this->assertSame([], $this->requested);
    }

    public function testIframeWithExistingLoadingAttributeIsUntouched(): void
    {
        $html = '<iframe loading="eager" src="/v"></iframe><IFRAME LOADING=lazy></IFRAME>';

        $this->assertSame($html, $this->process($this->plugin(false, true, false), $html));
    }

    public function testIframeIgnoredWhenFeatureDisabled(): void
    {
        $html = '<iframe src="/v"></iframe>';

        $this->assertSame($html, $this->process($this->plugin(false, false, true), $html));
    }

    public function testFontDisplaySwapAddedToFontFaceRulesInStyleBlocks(): void
    {
        $html = '<style>@font-face { font-family: A; src: url(a.woff2); } body{color:red}</style>';

        $this->assertSame(
            '<style>@font-face{font-display:swap; font-family: A; src: url(a.woff2); } body{color:red}</style>',
            $this->process($this->plugin(false, false, true), $html)
        );
    }

    public function testExistingFontDisplayIsRespected(): void
    {
        $html = '<style>@font-face{font-family:A;font-display:optional}@FONT-FACE{font-family:B}</style>';

        $this->assertSame(
            '<style>@font-face{font-family:A;font-display:optional}'
            . '@font-face{font-display:swap;font-family:B}</style>',
            $this->process($this->plugin(false, false, true), $html)
        );
    }

    public function testFontFaceOutsideStyleBlocksIsNotRewritten(): void
    {
        $html = '<p>@font-face{font-family:A}</p><script>var c="@font-face{x:y}";</script>';

        $this->assertSame($html, $this->process($this->plugin(false, false, true), $html));
    }

    public function testStyleBlockWithoutFontFaceIsUnchanged(): void
    {
        $html = '<style>body{margin:0}</style><p>@font-face</p>';

        $this->assertSame($html, $this->process($this->plugin(false, false, true), $html));
    }

    public function testFontsUntouchedWhenFeatureDisabled(): void
    {
        $html = '<style>@font-face{font-family:A}</style><img src="/media/a.png">';

        $this->assertSame(
            '<style>@font-face{font-family:A}</style><img width="800" height="600" src="/media/a.png">',
            $this->process($this->plugin(true, false, false, $this->dims()), $html)
        );
    }

    public function testAllFeaturesTogether(): void
    {
        $html = '<html><head><style>@font-face{font-family:A}</style></head>'
            . '<body><img src="/media/a.png"><iframe src="/v"></iframe></body></html>';

        $this->assertSame(
            '<html><head><style>@font-face{font-display:swap;font-family:A}</style></head>'
            . '<body><img width="800" height="600" src="/media/a.png"><iframe loading="lazy" src="/v"></iframe>'
            . '</body></html>',
            $this->process($this->plugin(dimensions: $this->dims()), $html)
        );
    }
}
