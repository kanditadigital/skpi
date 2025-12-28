@component('mail::message')

# Reset Password Akun SKPI Digital

Halo {{ $name }},

Password untuk akun SKPI Digital ({{ $email }}) telah direset oleh administrator. Silakan gunakan detail baru berikut:

@component('mail::panel')
Password baru: **{{ $password }}**
@endcomponent

Segera login dan ganti password ke yang lebih mudah diingat. Jika Anda tidak meminta perubahan ini, hubungi admin prodi.

Terima kasih,<br>
{{ config('app.name') }}

@endcomponent
