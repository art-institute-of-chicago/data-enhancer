<?php

namespace Tests\Concerns;

use Illuminate\Http\UploadedFile;

trait ImportsCsv
{
    private function importCsv(
        string $resourceName,
        string $csvContents
    ) {
        $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContents);

        $response = $this->post('/csv/import', [
            'resource' => $resourceName,
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        return $response;
    }
}
