<?php

namespace Tests\Feature;

use Tests\Concerns\HasFakeModel;
use Illuminate\Support\Facades\Config;

use App\Transformers\Outbound\Api\AbstractTransformer as BaseTransformer;

use Aic\Hub\Foundation\Testing\FeatureTestCase as BaseTestCase;

class ApiControllerTest extends BaseTestCase
{
    use HasFakeModel;

    protected function setUp(): void
    {
        parent::setUp();

        $transformerClass = new class() extends BaseTransformer {
            public function transform($item)
            {
                return parent::transform([
                    'id' => $item->id,
                    // ...other fields don't matter
                ]);
            }
        };

        Config::set('aic.output.api', [
            'v1' => [
                'foos' => [
                    'has_endpoint' => true,
                    'model' => $this->modelClass,
                    'transformer' => $transformerClass,
                ],
                'bars' => [
                    'has_endpoint' => false,
                    'model' => $this->modelClass,
                    'transformer' => $transformerClass,
                ],
                'bazs' => [
                    'model' => $this->modelClass,
                    'transformer' => $transformerClass,
                ],
            ],
        ]);
    }

    public function test_it_200s_on_resource_index()
    {
        $response = $this->get('/api/v1/foos');
        $response->assertStatus(200);
    }

    public function test_it_200s_on_resource_show()
    {
        $foo = ($this->modelClass)::factory()->create();
        $response = $this->get('/api/v1/foos/' . $foo->id);
        $response->assertStatus(200);
        $this->assertEquals($foo->getKey(), $response['data']['id']);
    }

    public function test_it_404s_on_bad_api_version()
    {
        $response = $this->get('/api/v2/foos');
        $response->assertStatus(404);
    }

    public function test_it_404s_on_bad_resource()
    {
        $response = $this->get('/api/v1/quxs');
        $response->assertStatus(404);
    }

    public function test_it_404s_on_resource_where_has_endpoint_is_false()
    {
        $response = $this->get('/api/v1/bars');
        $response->assertStatus(404);
    }

    public function test_it_404s_on_resource_where_has_endpoint_is_missing()
    {
        $response = $this->get('/api/v1/bars');
        $response->assertStatus(404);
    }
}
