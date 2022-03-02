<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aic\Hub\Foundation\Exceptions\DetailedException;
use Aic\Hub\Foundation\AbstractController as BaseController;

class ApiController extends BaseController
{
    public function showResource(Request $request, $apiVersion, $resourceName, $id)
    {
        $resourceConfig = $this->getResourceConfig($apiVersion, $resourceName);
        $this->setModelTransformer($resourceConfig);
        return parent::show(...func_get_args());
    }

    public function indexResource(Request $request, $apiVersion, $resourceName)
    {
        $resourceConfig = $this->getResourceConfig($apiVersion, $resourceName);
        $this->setModelTransformer($resourceConfig);
        return parent::index(...func_get_args());
    }

    private function setModelTransformer($resourceConfig)
    {
        $this->model = $resourceConfig['model'];
        $this->transformer = $resourceConfig['transformer'];
    }

    private function getResourceConfig($apiVersion, $resourceName)
    {
        if (!config('aic.output.api.' . $apiVersion)) {
            throw new DetailedException(
                'Invalid API version',
                'You requested an API version that does not exist.',
                404
            );
        }

        $resourceConfig = config('aic.output.api.' . $apiVersion . '.' . $resourceName);

        if (!$resourceConfig) {
            throw new DetailedException(
                'Resource not found',
                'You requested a resource that does not exist.',
                404
            );
        }

        if (!($resourceConfig['has_endpoint'] ?? false)) {
            throw new DetailedException(
                'Resource has no endpoint',
                'You requested a resource that has no endpoint.',
                404
            );
        }

        return $resourceConfig;
    }
}
