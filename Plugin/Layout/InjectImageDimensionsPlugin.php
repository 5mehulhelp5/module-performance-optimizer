<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Plugin\Layout;

use Magento\Framework\View\LayoutInterface;
use Panth\PerformanceOptimizer\Helper\Data as PerformanceHelper;
use Panth\PerformanceOptimizer\Service\ImageDimensionRegistry;

class InjectImageDimensionsPlugin
{
    public function __construct(
        private readonly PerformanceHelper $helper,
        private readonly ImageDimensionRegistry $registry
    ) {
    }

    public function afterGetOutput(LayoutInterface $subject, $result)
    {
        if (!is_string($result) || $result === '') {
            return $result;
        }
        if (!$this->helper->isSetImageDimensionsEnabled()) {
            return $result;
        }
        if (stripos($result, '<img') === false) {
            return $result;
        }

        return preg_replace_callback(
            '/<img\b[^>]*>/i',
            function (array $match): string {
                return $this->processTag($match[0]);
            },
            $result
        ) ?? $result;
    }

    private function processTag(string $tag): string
    {
        $hasWidth = (bool) preg_match('/\swidth\s*=\s*["\']?\d/i', $tag);
        $hasHeight = (bool) preg_match('/\sheight\s*=\s*["\']?\d/i', $tag);
        if ($hasWidth && $hasHeight) {
            return $tag;
        }

        if (!preg_match('/\ssrc\s*=\s*("([^"]+)"|\'([^\']+)\')/i', $tag, $m)) {
            return $tag;
        }
        $src = $m[2] !== '' ? $m[2] : ($m[3] ?? '');
        if ($src === '') {
            return $tag;
        }

        $dims = $this->registry->getDimensions($src);
        if ($dims === null) {
            return $tag;
        }

        $injected = '';
        if (!$hasWidth) {
            $injected .= ' width="' . $dims['width'] . '"';
        }
        if (!$hasHeight) {
            $injected .= ' height="' . $dims['height'] . '"';
        }
        if ($injected === '') {
            return $tag;
        }
        return preg_replace('/<img\b/i', '<img' . $injected, $tag, 1) ?? $tag;
    }
}
