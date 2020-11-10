<?php

namespace Tests\Unit;

use App\Http\Requests\RemittanceStoreRequest;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

class StoreRemittnaceRequestTest extends TestCase
{

    /**
     * @var RemittanceStoreRequest
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new RemittanceStoreRequest();
    }

    public function testRules()
    {
        $this->assertEquals([
            'value' => 'required|numeric|min:1|max:9999999999',
            'payer_id' => 'required|numeric|min:1',
            'recipient_id' => 'required|numeric|min:1|different:payer_id',
            'do_at' => 'required|date|after:now',
        ],
            $this->subject->rules()
        );
    }

    public function testAuthorize()
    {
        $this->assertTrue($this->subject->authorize());
    }
}
