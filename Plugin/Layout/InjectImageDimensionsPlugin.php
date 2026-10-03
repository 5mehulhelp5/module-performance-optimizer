<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Plugin\Layout;

use Magento\Framework\View\LayoutInterface;
use Panth\PerformanceOptimizer\Helper\Data as PerformanceHelper;
use Panth\PerformanceOptimizer\Service\ImageDimensionRegistry;

class InjectImageDimensionsPlugin
{
    private const TAG_PATTERN = '/\G<([a-zA-Z][a-zA-Z0-9:-]*)(?:[^>"\']++|"[^"]*+"|\'[^\']*+\')*+>/';

    private const ATTRIBUTE_PATTERN = '/([^\s"\'>\/=]+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+)))?/';

    private const SKIPPED_CONTENT = [
        'script' => true,
        'style' => true,
        'template' => true,
        'textarea' => true,
        'noscript' => true,
        'title' => true,
        'xmp' => true,
    ];

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

        $images = $this->helper->isSetImageDimensionsEnabled() && stripos($result, '<img') !== false;
        $iframes = $this->helper->isIframeLazyLoadEnabled() && stripos($result, '<iframe') !== false;
        $fonts = $this->helper->isFontDisplaySwapEnabled() && stripos($result, '@font-face') !== false;
        if (!$images && !$iframes && !$fonts) {
            return $result;
        }

        return $this->rewrite($result, $images, $iframes, $fonts);
    }

    private function rewrite(string $html, bool $images, bool $iframes, bool $fonts): string
    {
        $length = strlen($html);
        $out = '';
        $pos = 0;

        while ($pos < $length) {
            $lt = strpos($html, '<', $pos);
            if ($lt === false) {
                break;
            }

            if (substr_compare($html, '<!--', $lt, 4) === 0) {
                $end = strpos($html, '-->', $lt + 4);
                $stop = $end === false ? $length : $end + 3;
                $out .= substr($html, $pos, $stop - $pos);
                $pos = $stop;
                continue;
            }

            if ($lt + 1 >= $length
                || !ctype_alpha($html[$lt + 1])
                || !preg_match(self::TAG_PATTERN, $html, $match, 0, $lt)
            ) {
                $out .= substr($html, $pos, $lt + 1 - $pos);
                $pos = $lt + 1;
                continue;
            }

            $tag = $match[0];
            $name = strtolower($match[1]);
            $tagEnd = $lt + strlen($tag);
            $out .= substr($html, $pos, $lt - $pos);

            if ($name === 'img' && $images) {
                $tag = $this->processImage($tag);
            } elseif ($name === 'iframe' && $iframes) {
                $tag = $this->processIframe($tag);
            }
            $out .= $tag;
            $pos = $tagEnd;

            if (isset(self::SKIPPED_CONTENT[$name])) {
                $close = $name === 'template'
                    ? $this->findTemplateEnd($html, $tagEnd)
                    : $this->findClosingTag($html, $name, $tagEnd);
                $inner = substr($html, $tagEnd, $close - $tagEnd);
                if ($name === 'style' && $fonts) {
                    $inner = $this->addFontDisplay($inner);
                }
                $out .= $inner;
                $pos = $close;
            }
        }

        if ($pos < $length) {
            $out .= substr($html, $pos);
        }

        return $out;
    }

    private function findClosingTag(string $html, string $name, int $offset): int
    {
        if (preg_match('#</' . $name . '(?=[\s/>])#i', $html, $match, PREG_OFFSET_CAPTURE, $offset)) {
            return (int) $match[0][1];
        }
        return strlen($html);
    }

    private function findTemplateEnd(string $html, int $offset): int
    {
        $depth = 1;
        while (preg_match('#<(/?)template(?=[\s/>])#i', $html, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $at = (int) $match[0][1];
            $offset = $at + strlen($match[0][0]);
            if ($match[1][0] === '') {
                $depth++;
                continue;
            }
            $depth--;
            if ($depth === 0) {
                return $at;
            }
        }
        return strlen($html);
    }

    private function parseAttributes(string $tag, int $nameLength): array
    {
        $body = substr($tag, $nameLength + 1, -1);
        $attributes = [];
        if (!preg_match_all(self::ATTRIBUTE_PATTERN, $body, $matches, PREG_SET_ORDER)) {
            return $attributes;
        }
        foreach ($matches as $match) {
            $key = strtolower($match[1]);
            if (isset($attributes[$key])) {
                continue;
            }
            $attributes[$key] = $match[2] ?? '';
            if ($attributes[$key] === '' && isset($match[3])) {
                $attributes[$key] = $match[3];
            }
            if ($attributes[$key] === '' && isset($match[4])) {
                $attributes[$key] = $match[4];
            }
        }
        return $attributes;
    }

    private function processImage(string $tag): string
    {
        $attributes = $this->parseAttributes($tag, 3);
        $hasWidth = array_key_exists('width', $attributes);
        $hasHeight = array_key_exists('height', $attributes);
        if ($hasWidth && $hasHeight) {
            return $tag;
        }

        $src = html_entity_decode(trim($attributes['src'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($src === '') {
            return $tag;
        }

        $dims = $this->registry->getDimensions($src);
        if ($dims === null || $dims['width'] <= 0 || $dims['height'] <= 0) {
            return $tag;
        }

        if (!$hasWidth && !$hasHeight) {
            $injected = ' width="' . $dims['width'] . '" height="' . $dims['height'] . '"';
        } elseif ($hasWidth) {
            $width = trim($attributes['width']);
            if (!ctype_digit($width) || (int) $width === 0) {
                return $tag;
            }
            $height = max(1, (int) round((int) $width * $dims['height'] / $dims['width']));
            $injected = ' height="' . $height . '"';
        } else {
            $height = trim($attributes['height']);
            if (!ctype_digit($height) || (int) $height === 0) {
                return $tag;
            }
            $width = max(1, (int) round((int) $height * $dims['width'] / $dims['height']));
            $injected = ' width="' . $width . '"';
        }

        return substr($tag, 0, 4) . $injected . substr($tag, 4);
    }

    private function processIframe(string $tag): string
    {
        $attributes = $this->parseAttributes($tag, 6);
        if (array_key_exists('loading', $attributes)) {
            return $tag;
        }
        return substr($tag, 0, 7) . ' loading="lazy"' . substr($tag, 7);
    }

    private function addFontDisplay(string $css): string
    {
        if (stripos($css, '@font-face') === false) {
            return $css;
        }
        return preg_replace_callback(
            '/@font-face\s*\{([^{}]*)\}/i',
            static function (array $match): string {
                if (stripos($match[1], 'font-display') !== false) {
                    return $match[0];
                }
                return '@font-face{font-display:swap;' . $match[1] . '}';
            },
            $css
        ) ?? $css;
    }
}
