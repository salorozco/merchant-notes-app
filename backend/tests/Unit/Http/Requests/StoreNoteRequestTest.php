<?php

namespace Tests\Unit\Http\Requests;

use Tests\TestCase;
use App\Http\Requests\StoreNoteRequest;

class StoreNoteRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function test_authorize_returns_true(): void
    {
        $request = new StoreNoteRequest();

        $this->assertTrue($request->authorize());
    }

    /**
     * @return void
     */
    public function test_body_field_is_required_and_must_be_string_with_minimum_length(): void
    {
        $request = new StoreNoteRequest();

        $expectedRules = [
            'body' => 'required|string|min:3',
        ];

        $this->assertEquals($expectedRules, $request->rules());
    }
}
