<?php

declare(strict_types=1);

namespace Clickonmedia\Manager\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Http\RedirectResponse;

interface DamInterface
{
    /**
     * Get the storage instance.
     */
    public function storage(): AwsS3V3Adapter;

    /**
     * Get the namespace of the service name.
     * ie: Clickonmedia\Dropbox\Dropbox
     */
    public function getInterfaceType(): string;

    /**
     * Get the service name.
     * ie: Dropbox
     * IMPORTANT: it has to be the same as Facades\PackageName::getFacadeAccessor()
     */
    public function getServiceName(): string;

    /**
     * Get the user information from the service.
     */
    public function user(): array;

    /**
     * Callback from oAuth URL.
     */
    public function read(array $tokens = []); // NO RETURN TYPE HINTING NEEDED.

    /**
     * Get all files from the service.
     */
    public function index(array $params = []): array;

    /**
     * Store by redirecting to oAuth URL for user to be able to authorize the application.
     */
    public function store(?Model $setting = null, string $email = ''): RedirectResponse;

    /**
     * Download a file from the service.
     */
    public function download(Model $file, string $storagePath = ''): string|bool;

    /**
     * Check $files record against the unique thing we can compare with in the service.
     */
    public function recordDoesntExist(Collection $files, mixed $file): bool;

    /**
     * Transforms a service keys to the service format.
     */
    public function transformService(array $service): array;

    /**
     * Get the thumbnail path.
     */
    public function getThumbnailPath(mixed $file): string;

    /**
     * Transforms a file keys to the file format.
     */
    public function transformFile(mixed $file, bool $createThumbnail = true): array;
}
