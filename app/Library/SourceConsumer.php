<?php

namespace App\Library;

use LogicException;
use GuzzleHttp\ClientInterface;

class SourceConsumer
{
    public static function get(string $sourceName, string $resourceName, array $params = [])
    {
        $sourceConfig = self::getSourceConfig($sourceName);

        $uri = sprintf('%s/%s?%s', ...[
            $sourceConfig['base_uri'],
            $resourceName,
            http_build_query($params),
        ]);

        $headers = [];

        $apiToken = $sourceConfig['api_token'];

        if (!empty($apiToken)) {
            $headers['Authorization'] = 'Bearer ' . $apiToken;
        }

        $client = app()->make(ClientInterface::class);
        $response = $client->request('GET', $uri, $headers);
        $contents = $response->getBody()->getContents();

        return json_decode($contents);
    }

    public static function getTotalPages(string $sourceName, string $resourceName)
    {
        // API-87: Setting `limit` to 0 does nothing at the moment!
        $result = self::get($sourceName, $resourceName, [
            'limit' => 0,
        ]);

        $limit = self::getLimit($sourceName, $resourceName);

        return ceil($result->pagination->total / $limit);
    }

    public static function getLimit(string $sourceName, string $resourceName)
    {
        return config(
            'aic.imports.sources.' . $sourceName . '.resources.' . $resourceName . '.limit',
            config(
                'aic.imports.sources.' . $sourceName . '.limit',
                config(
                    'aic.imports.limit',
                    100
                )
            )
        );
    }

    /**
     * Should we validate config via tests instead?
     */
    public static function getSourceConfig(string $sourceName)
    {
        $sourceConfig = config('aic.imports.sources.' . $sourceName);

        if (!$sourceConfig) {
            throw new LogicException("Missing config for source '{$sourceName}'");
        }

        if (empty($sourceConfig['base_uri'])) {
            throw new LogicException("No 'base_uri' defined for source '{$sourceName}'");
        }

        $sourceConfig['base_uri'] = rtrim($sourceConfig['base_uri'], '/');

        if (empty($sourceConfig['resources'])) {
            throw new LogicException("No 'resources' defined for source '{$sourceName}'");
        }

        foreach ($sourceConfig['resources'] as $resourceName => $resourceConfig) {
            self::validateResourceConfig($sourceName, $resourceName, $resourceConfig);
        }

        return $sourceConfig;
    }

    public static function getResourceConfig(string $sourceName, string $resourceName)
    {
        $sourceConfig = self::getSourceConfig($sourceName);
        $resourceConfig = $sourceConfig['resources'][$resourceName] ?? null;

        if (empty($sourceConfig)) {
            throw new LogicException("Source '{$sourceName}' missing resource '{$resourceName}'");
        }

        self::validateResourceConfig($sourceName, $resourceName, $resourceConfig);

        return $resourceConfig;
    }

    private static function validateResourceConfig(string $sourceName, string $resourceName, array $resourceConfig)
    {
        foreach (['model', 'transformer', 'has_endpoint'] as $key) {
            if (empty($resourceConfig[$key])) {
                throw new LogicException("Resource '{$resourceName}' in '{$sourceName}' missing '{$key}'");
            }
        }

        foreach (['model', 'transformer'] as $key) {
            if (!class_exists($resourceConfig[$key])) {
                throw new LogicException("Class '{$resourceConfig[$key]}' required by '{$resourceName}' not found");
            }
        }
    }
}
