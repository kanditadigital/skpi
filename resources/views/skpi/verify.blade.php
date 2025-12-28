<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi SKPI {{ $document->nomor_skpi }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f2f4f7;
            color: #111;
        }
        .container {
            max-width: 720px;
            margin: 48px auto;
            background: #fff;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        h1 {
            margin-top: 0;
            font-size: 22px;
            letter-spacing: 0.05em;
        }
        .meta {
            margin: 16px 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
        }
        .meta-item {
            padding: 10px 12px;
            border: 1px solid #d0d7df;
            border-radius: 6px;
            background: #f9fbfe;
            font-size: 14px;
        }
        .meta-item strong {
            display: block;
            font-size: 12px;
            color: #555;
            margin-bottom: 4px;
        }
        .meta-note {
            font-size: 12px;
            color: #5b636f;
            margin-top: 4px;
        }
        .status {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .status.valid {
            background: #dff0e1;
            color: #1a7f3c;
        }
        .hash-code {
            font-family: Menlo, monospace;
            font-size: 13px;
            word-break: break-all;
            background: #f4f6fb;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #e0e6ef;
        }
        .note {
            font-size: 13px;
            color: #4d5563;
            margin-top: 16px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Verifikasi SKPI</h1>
    <p>Nomor SKPI ini telah diterbitkan secara resmi dan dapat dipastikan keasliannya melalui QR code atau kode verifikasi di bawah.</p>

    <div class="meta">
        <div class="meta-item">
            <strong>Nomor SKPI</strong>
            {{ $document->nomor_skpi }}
        </div>
        <div class="meta-item">
            <strong>Nama Pemilik</strong>
            {{ $profile?->nama_lengkap ?? 'Tidak tersedia' }}
            <div class="meta-note">NIM: {{ $profile?->nim ?? '-' }}</div>
        </div>
        <div class="meta-item">
            <strong>Tanggal Terbit</strong>
            {{ $document->issued_at?->format('d F Y H:i') ?? '-' }}
        </div>
        <div class="meta-item">
            <strong>Status Keaslian</strong>
            <span class="status valid">Valid</span>
        </div>
    </div>

    <div class="hash-code">
        Hash Verifikasi: {{ $document->hash }}
    </div>

    <p class="note">
        Jika Anda mencapai halaman ini melalui QR code pada dokumen SKPI, maka dokumen tersebut sesuai dengan catatan kami.
        Untuk pertanyaan lebih lanjut silakan hubungi tim administrasi kampus.
    </p>
</div>
</body>
</html>
