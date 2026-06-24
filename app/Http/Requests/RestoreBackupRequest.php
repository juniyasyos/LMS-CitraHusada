<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RestoreBackupRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Hanya Super Admin (role_id = 1) yang boleh restore
        return $this->user() && $this->user()->hasRole('super_admin');
    }

    /**
     * Aturan validasi untuk request restore.
     */
    public function rules(): array
    {
        return [
            'backup_file' => ['required', 'string'],
            'password'    => ['required', 'string'],
        ];
    }

    /**
     * Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'backup_file.required' => 'File backup harus dipilih.',
            'password.required'    => 'Password harus diisi untuk konfirmasi restore.',
        ];
    }

    /**
     * Validasi tambahan setelah rules utama lolos.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validasi password user yang sedang login
            if ($this->password && !Hash::check($this->password, $this->user()->password)) {
                $validator->errors()->add('password', 'Password tidak valid. Silakan coba lagi.');
            }

            // Validasi file backup ada di disk
            if ($this->backup_file && !Storage::disk(config('filesystems.default', 'local'))->exists($this->backup_file)) {
                $validator->errors()->add('backup_file', 'File backup tidak ditemukan di server.');
            }
        });
    }
}
