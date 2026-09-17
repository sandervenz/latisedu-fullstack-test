<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SiswaValidationTest extends TestCase
{
    protected array $rules = [
        'nis' => ['required', 'numeric', 'regex:/^[0-9]+$/'],
        'email' => ['required', 'email:rfc,filter', 'max:255'],
    ];

    public function test_nis_rejects_alphabetic_characters(): void
    {
        $validator = Validator::make([
            'nis' => '1004a',
            'email' => 'siswa@latiseducation.com',
        ], $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('nis', $validator->errors()->toArray());
    }

    public function test_nis_rejects_symbols(): void
    {
        $validator = Validator::make([
            'nis' => '1004-9',
            'email' => 'siswa@latiseducation.com',
        ], $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('nis', $validator->errors()->toArray());
    }

    public function test_nis_accepts_pure_numeric_digits(): void
    {
        $validator = Validator::make([
            'nis' => '20261001',
            'email' => 'siswa@latiseducation.com',
        ], $this->rules);

        $this->assertFalse($validator->fails());
    }

    public function test_email_rejects_plain_string_without_at_symbol(): void
    {
        $validator = Validator::make([
            'nis' => '20261001',
            'email' => 'bukanemail',
        ], $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_rejects_email_without_tld_domain(): void
    {
        $validator = Validator::make([
            'nis' => '20261001',
            'email' => 'siswa@invalidhost',
        ], $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_accepts_valid_format(): void
    {
        $validator = Validator::make([
            'nis' => '20261001',
            'email' => 'budi.santoso@latiseducation.com',
        ], $this->rules);

        $this->assertFalse($validator->fails());
    }
}
