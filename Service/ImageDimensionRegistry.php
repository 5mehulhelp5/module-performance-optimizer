<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Service;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;

class ImageDimensionRegistry
{
    private const CACHE_KEY_PREFIX = 'panth_perf_imgdim_';
    private const CACHE_TAG = 'panth_perf_imgdim';
    private const CACHE_LIFETIME = 86400;

    private array $memo = [];

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly Filesystem $filesystem,
        private readonly StoreManagerInterface $storeManager,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function getDimensions(string $src): ?array
    {
        $key = $this->normalize($src);
        if ($key === null) {
            return null;
        }
        if (array_key_exists($key, $this->memo)) {
            return $this->memo[$key];
        }

        $cacheKey = self::CACHE_KEY_PREFIX . md5($key);
        $cached = $this->cache->load($cacheKey);
        if ($cached !== false) {
            try {
                $value = $this->serializer->unserialize($cached);
                $this->memo[$key] = is_array($value) ? $value : null;
                return $this->memo[$key];
            } catch (\Throwable) {
            }
        }

        $dims = $this->resolveFromDisk($key);
        $this->memo[$key] = $dims;
        $this->cache->save(
            $this->serializer->serialize($dims),
            $cacheKey,
            [self::CACHE_TAG],
            self::CACHE_LIFETIME
        );
        return $dims;
    }

    private function normalize(string $src): ?string
    {
        $src = trim($src);
        if ($src === '' || str_starts_with($src, 'data:')) {
            return null;
        }
        $src = preg_replace('/[?#].*$/', '', $src) ?? $src;
        if ($src === '') {
            return null;
        }

        if (preg_match('#^https?://([^/]+)(/.*)?$#i', $src, $m)) {
            try {
                $base = parse_url($this->storeManager->getStore()->getBaseUrl(), PHP_URL_HOST);
            } catch (\Throwable) {
                $base = null;
            }
            if ($base === null || strcasecmp((string) $base, $m[1]) !== 0) {
                return null;
            }
            $src = $m[2] ?? '/';
        }
        if (!str_starts_with($src, '/')) {
            return null;
        }

        $src = preg_replace('#^/static/version\d+/#', '/static/', $src) ?? $src;
        return $src;
    }

    private function resolveFromDisk(string $path): ?array
    {
        try {
            $pubDir = $this->filesystem->getDirectoryRead(DirectoryList::PUB);
        } catch (\Throwable) {
            return null;
        }

        $relative = ltrim($path, '/');
        $absolute = $pubDir->getAbsolutePath($relative);

        if (!is_file($absolute) || !is_readable($absolute)) {
            return null;
        }
        $info = @getimagesize($absolute);
        if (!is_array($info) || empty($info[0]) || empty($info[1])) {
            return null;
        }
        return ['width' => (int) $info[0], 'height' => (int) $info[1]];
    }
}
