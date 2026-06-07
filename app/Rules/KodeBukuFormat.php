<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KodeBukuFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Regex untuk mencocokkan format BK-[A-Z 2 sampai 4 huruf]-[3 digit angka]
        if (!preg_match('/^BK-[A-Z]{2,4}-\d{3}$/', $value)) {
            $fail('Format kode buku harus: BK-XXX-000 (contoh: BK-PROG-001).');
        }
    }
}