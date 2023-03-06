<?php

namespace Clickonmedia\Manager;

use Clickonmedia\Manager\Interfaces\DamInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\AwsS3V3Adapter;
use RuntimeException;
use Storage;

abstract class Manager implements DamInterface
{
    public ?Model $service;

    public ?Model $setting;

    public AwsS3V3Adapter $storage;

    public function storage(): AwsS3V3Adapter
    {
        if ($this->setting &&
            ($this->setting->aws_id
                || $this->setting->aws_secret
                || $this->setting->aws_region
                || $this->setting->aws_bucket)) {
            throw_if(
                ! $this->setting->aws_id
                && ! $this->setting->aws_secret
                && ! $this->setting->aws_region
                && ! $this->setting->aws_bucket,
                RuntimeException::class,
                'AWS credentials are not fully set.'
            );

            // By here, all AWS credentials are set
            return $this->storage = Storage::build([
                'driver'                  => 's3',
                'key'                     => $this->setting->aws_id,
                'secret'                  => $this->setting->aws_secret,
                'region'                  => $this->setting->aws_region,
                'bucket'                  => $this->setting->aws_bucket,
                'url'                     => env('AWS_URL'),
                'endpoint'                => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
                'visibility'              => 'public',
            ]);
        } else {
            return $this->storage = Storage::disk('s3');
        }
    }

    public function transformService(array $service): array
    {
        return [
            'user_id'        => auth()?->id(),
            'setting_id'     => $service['setting_id'] ?? null,
            'email'          => $this->user()['email'] ?? $service['email'],
            'photo'          => $this->user()['photo'] ?? $service['photo'],
            'name'           => $this->getServiceName(),
            'interface_type' => $this->getInterfaceType(),
            'ip_address'     => request()?->ip(),
            'status'         => 'active',
            'access_token'   => $service['access_token'] ?? null,
            'refresh_token'  => $service['refresh_token'] ?? null,
            'api_key'        => $service['api_key'] ?? null,
            'expires'        => $service['expires'] ?? null,
            'created'        => $service['created'] ?? null,
            'options'        => $service['options'] ?? json_encode($service) ?? null,
            'password'       => $service['password'] ?? null,
        ];
    }

    public function getInterfaceType(): string
    {
        return __CLASS__;
    }

    public function getServiceName(): string
    {
        return 'dropbox';
    }
}
