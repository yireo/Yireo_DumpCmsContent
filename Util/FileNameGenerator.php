<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Util;

class FileNameGenerator
{
    private const MAX_IDENTIFIER_LENGTH = 100;

    public function generate(string $prefix, int $entityId, string $identifier): string
    {
        $safeIdentifier = trim((string)preg_replace('/[^a-z0-9_-]+/i', '_', $identifier), '_');
        $safeIdentifier = substr($safeIdentifier, 0, self::MAX_IDENTIFIER_LENGTH);
        if ($safeIdentifier === '') {
            $safeIdentifier = 'empty';
        }

        return $prefix . '-' . $entityId . '-' . $safeIdentifier . '.html';
    }
}
