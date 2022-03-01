<?php

namespace Tests\Unit;

use App\Transformers\Datum;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;

class DatumTest extends TestCase
{
    private function getModel($input)
    {
        return new class($input) extends BaseModel {
            protected $guarded = [];
        };
    }

    /**
     * @todo Add test for Carbon value?
     */
    private function getInput()
    {
        $input = [
            'foo' => 1,
            'bar' => null,
            'baz' => '',
            'qux' => ' ',
            'quux' => 'quux',
            'corge' => 'corge ',
        ];

        $input = array_merge($input, [
            'grault' => $input,
            'garply' => (object) $input,
            'waldo' => $this->getModel($input),
        ]);

        $input = array_merge($input, [
            'fred' => array_values($input),
            'plugh' => collect(array_values($input)),
        ]);

        return $input;
    }

    private function checkDatum($datum)
    {
        $this->checkDatumFields($datum);
        $this->checkDatumFields($datum->grault);
        $this->checkDatumFields($datum->garply);
        $this->checkDatumFields($datum->waldo);

        foreach (['fred', 'plugh'] as $key) {
            $value = $datum->{$key};
            $this->assertFalse(Arr::isAssoc($value));
            $this->assertEquals(1, $value[0]);
            $this->assertEquals('quux', $value[1]);
            $this->assertEquals('corge', $value[2]);
            $this->checkDatumFields($value[3]);
            $this->checkDatumFields($value[4]);
            $this->checkDatumFields($value[5]);
        }
    }

    private function checkDatumFields($datum)
    {
        $this->assertTrue($datum instanceof Datum);
        $this->assertEquals(1, $datum->foo);
        $this->assertEquals(null, $datum->bar);
        $this->assertEquals(null, $datum->baz);
        $this->assertEquals(null, $datum->qux);
        $this->assertEquals('quux', $datum->quux);
        $this->assertEquals('corge', $datum->corge);
    }

    public function test_one_datum_from_array()
    {
        $input = $this->getInput();
        $datum = new Datum($input);
        $this->checkDatum($datum);
    }

    public function test_one_datum_from_object()
    {
        $input = (object) $this->getInput();
        $datum = new Datum($input);
        $this->checkDatum($datum);
    }

    public function test_one_datum_from_model()
    {
        $input = $this->getModel($this->getInput());
        $datum = new Datum($input);
        $this->checkDatum($datum);
    }
}
