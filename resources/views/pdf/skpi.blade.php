<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKPI - {{ $alumni->nama_lengkap }}</title>
    <style>
        /* CSS Variables for consistent styling */
        :root {
            --font-size-base: 11px;
            --color-text: #111;
            --color-text-secondary: #333;
            --color-border: #111;
            --color-background-light: #f4f4f4;
            --color-background-qr: #fafafa;
            --margin-small: 4px;
            --margin-medium: 8px;
            --margin-large: 16px;
            --margin-xlarge: 24px;
            --padding-small: 4px 6px;
            --padding-medium: 6px 8px;
            --padding-large: 12px;
            --border-radius: 6px;
        }

        /* Base Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: var(--font-size-base);
            color: var(--color-text);
        }

        .page {
            padding: 32px;
            position: relative;
        }

        /* Header Styles */
        header {
            padding-bottom: var(--margin-large);
            margin-bottom: var(--margin-large);
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            width: 100%;
        }

        .institution {
            text-align: center;
            flex: 1;
            margin-left: var(--margin-large);
        }

        .institution h1 {
            margin: 0;
            font-size: var(--font-size-base);
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .institution p,
        .document-title p {
            margin: 2px 0;
            font-size: var(--font-size-base);
        }

        .document-title {
            text-align: center;
            margin-top: 12px;
        }

        .document-title h2 {
            margin: 0;
            font-size: var(--font-size-base);
            letter-spacing: .05em;
        }

        .centered {
            text-align: center;
            margin: 12px 0;
            font-weight: bold;
            font-size: var(--font-size-base);
        }

        /* Table Base Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: var(--margin-large);
            font-size: var(--font-size-base);
        }

        table td, table th {
            vertical-align: top;
            padding: 2px 0;
            font-size: var(--font-size-base);
        }

        table.titled td:first-child {
            width: 230px;
            font-weight: 600;
        }

        /* Personal Info Table */
        .personal-info {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: var(--margin-xlarge);
        }

        .personal-info td {
            border: 1px solid var(--color-border);
            padding: var(--padding-small);
            vertical-align: top;
        }

        .personal-info .number {
            width: 70px;
            font-weight: 700;
            text-align: center;
        }

        .personal-info .label {
            width: 260px;
            font-weight: 700;
            font-size: var(--font-size-base);
        }

        .personal-info .label-english {
            font-size: var(--font-size-base);
            font-style: italic;
            color: var(--color-text-secondary);
            display: block;
            margin-top: var(--margin-small);
        }

        .personal-info .value {
            font-size: var(--font-size-base);
        }

        .personal-info .value-english {
            font-size: var(--font-size-base);
            font-style: italic;
            color: var(--color-text-secondary);
            margin-top: var(--margin-small);
            display: block;
        }

        .personal-info .value-neutral {
            font-size: var(--font-size-base);
            color: var(--color-text-secondary);
            margin-top: var(--margin-small);
            display: block;
        }

        /* Learning Tables */
        .learning-main-table,
        .learning-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: var(--margin-large);
        }

        .learning-main-table td,
        .learning-table th,
        .learning-table td {
            border: 1px solid var(--color-border);
            padding: var(--padding-medium);
            vertical-align: top;
            font-size: var(--font-size-base);
        }

        .learning-main-table td {
            font-weight: 600;
        }

        .learning-main-table .learning-letter {
            width: 50px;
            text-align: center;
            font-size: var(--font-size-base);
        }

        .learning-main-table .learning-title {
            font-size: var(--font-size-base);
            text-transform: uppercase;
        }

        .learning-main-table .learning-title .learning-subtext {
            font-size: var(--font-size-base);
            font-style: italic;
            display: block;
            margin-top: var(--margin-small);
        }

        .learning-main-table .learning-program {
            text-align: center;
        }

        .learning-main-table .learning-program-sub,
        .learning-table .learning-heading-english {
            font-size: var(--font-size-base);
            font-style: italic;
            display: block;
            margin-top: var(--margin-small);
        }

        .learning-content-english {
            font-style: italic;
        }

        .english-column {
            font-style: italic;
        }

        .learning-table th {
            background: var(--color-background-light);
            font-weight: 700;
            text-align: left;
            font-size: var(--font-size-base);
        }

        .learning-table .learning-number {
            width: 40px;
            font-weight: 700;
        }

        .learning-table td p {
            margin: 6px 0 0;
            font-size: var(--font-size-base);
        }

        /* Bilingual Tables */
        .bilingual-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: var(--margin-large);
        }

        .bilingual-table th,
        .bilingual-table td {
            border: 1px solid var(--color-border);
            padding: var(--padding-medium);
            vertical-align: top;
        }

        .bilingual-table th {
            background: var(--color-background-light);
            font-size: var(--font-size-base);
            text-align: left;
        }

        .bilingual-list {
            margin: var(--margin-medium) 0 0 18px;
            padding: 0;
        }

        .bilingual-list li {
            margin-bottom: var(--margin-small);
        }

        /* Section Styles */
        .section-title {
            font-size: var(--font-size-base);
            font-weight: 700;
            margin: var(--margin-xlarge) 0 var(--margin-medium);
            text-transform: uppercase;
        }

        .section-subtitle {
            font-size: var(--font-size-base);
            margin-bottom: var(--margin-medium);
            font-style: italic;
        }

        /* List Styles */
        ul {
            margin: 0 0 var(--margin-large) 18px;
            padding: 0;
        }

        ul li {
            margin-bottom: 6px;
            font-size: var(--font-size-base);
        }

        .activities-list {
            margin-top: var(--margin-medium);
            font-size: var(--font-size-base);
        }

        /* QR Code Corner */
        .qr-corner {
            position: absolute;
            right: 32px;
            bottom: 32px;
            width: 180px;
            text-align: center;
            font-size: var(--font-size-base);
            border: 1px solid var(--color-border);
            padding: var(--padding-large);
            border-radius: var(--border-radius);
            background: var(--color-background-qr);
        }

        .qr-corner .qr-image {
            width: 130px;
            height: 130px;
            margin: 0 auto var(--margin-medium);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-corner .qr-image img {
            max-width: 100%;
            max-height: 100%;
        }

        .qr-corner .qr-label {
            font-size: var(--font-size-base);
            word-break: break-all;
            margin-top: var(--margin-small);
        }

        /* Footer Signature */
        .footer-signature {
            margin-top: 36px;
            display: flex;
            justify-content: flex-start;
            gap: 32px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .footer-signature .signature-box:first-child {
            text-align: left;
        }

        .signature-box .label {
            font-size: var(--font-size-base);
        }

        .signature-box img {
            max-width: 180px;
            margin-bottom: var(--margin-medium);
        }

        .signature-box .name {
            font-weight: 700;
            margin-top: var(--margin-small);
        }

        /* Opening Text Styles */
        .opening-text {
            font-size: var(--font-size-base);
            text-align: justify;
        }

        .opening-text-english {
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="page">
    <header>
        <div class="header-top">
            <div class="logo">
                @if(!empty($institution->kop_surat_url))
                    <img src="{{ $institution->kop_surat_url }}" alt="Kop Surat">
                @elseif(!empty($institution->logo_url))
                    <img src="{{ $institution->logo_url }}" alt="Logo">
                @endif
            </div>
        </div>
        <div class="document-title">
            <h2>SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)</h2>
            <p>Bachelor Supplement</p>
        </div>
    </header>

    <div class="centered">
        Nomor SKPI: {{ $document->nomor_skpi }}
    </div>

    @if(!empty($document->opening_text_id) || !empty($document->opening_text_en))
        <section>
            @if(!empty($document->opening_text_id))
                <p class="opening-text">{{ $document->opening_text_id }}</p>
            @endif
            @if(!empty($document->opening_text_en))
                <p class="opening-text opening-text-english">{{ $document->opening_text_en }}</p>
            @endif
        </section>
    @endif

    <section>
        <div class="section-title">1. INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI</div>
        <div class="section-subtitle">Information Identifying The Holder of Bachelor Supplement</div>
        @php
            $birthPlace = $alumni->tempat_lahir;
            $birthDate = optional($alumni->tanggal_lahir)->format('d F Y');
            $birthValue = array_filter([$birthPlace, $birthDate], fn($value) => !empty($value))
                ? implode(', ', array_filter([$birthPlace, $birthDate], fn($value) => !empty($value)))
                : '-';
            $degreeEnglish = config('skpi.document.degree_title_en');
        @endphp
        <table class="personal-info">
            <tr>
                <td class="number">1.1</td>
                <td class="label">
                    NAMA LENGKAP
                    <span class="label-english">Full Name</span>
                </td>
                <td class="value">{{ $alumni->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">1.2</td>
                <td class="label">
                    TEMPAT DAN TANGGAL LAHIR
                    <span class="label-english">Date and Place of Birth</span>
                </td>
                <td class="value">{{ $birthValue }}</td>
            </tr>
            <tr>
                <td class="number">1.3</td>
                <td class="label">
                    NOMOR INDUK MAHASISWA
                    <span class="label-english">Student Identification Number</span>
                </td>
                <td class="value">{{ $alumni->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">1.4</td>
                <td class="label">
                    TAHUN LULUS
                    <span class="label-english">Year of Completion</span>
                </td>
                <td class="value">{{ $alumni->tahun_lulus ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">1.5</td>
                <td class="label">
                    NOMOR IJAZAH
                    <span class="label-english">Bachelor Number</span>
                </td>
                <td class="value">{{ $alumni->nomor_ijazah ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">1.6</td>
                <td class="label">
                    GELAR
                    <span class="label-english">Title</span>
                </td>
                <td class="value">
                    {{ $alumni->gelar_akademik ?? '-' }}
                    @if($degreeEnglish)
                        <span class="value-english">{{ $degreeEnglish }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </section>

    <section>
        <div class="section-title">2. INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM</div>
        <div class="section-subtitle">Information about the Program Provider</div>
        @php
            $issuedDateId = optional($document->issued_at)->format('d F Y');
            $issuedDateEn = optional($document->issued_at)->format('F j, Y');

            $institutionRows = [
                [
                    'number' => '2.1',
                    'label' => 'SK Pendirian Perguruan Tinggi',
                    'label_en' => 'Awarding Institution\'s License',
                    'value' => $institution->sk_pendirian ?? '-',
                    'value_secondary' => $institution->sk_pendirian_date ?? $issuedDateId,
                    'value_secondary_en' => $institution->sk_pendirian_date_en ?? $issuedDateEn,
                ],
                [
                    'number' => '2.2',
                    'label' => 'Nama Perguruan Tinggi',
                    'label_en' => 'Awarding Institution\'s Name',
                    'value' => strtoupper($institution->name ?? '-'),
                    'value_en' => $institution->name_en ?? '',
                ],
                [
                    'number' => '2.3',
                    'label' => 'Program Studi',
                    'label_en' => 'Study Program',
                    'value' => strtoupper($institution->program_studi ?? '-'),
                    'value_en' => $institution->program_studi_en ?? '',
                ],
                [
                    'number' => '2.4',
                    'label' => 'Jenis dan Jenjang Pendidikan',
                    'label_en' => 'Type and Level of Education',
                    'value' => $institution->jenis_pendidikan ?? '-',
                    'value_en' => $institution->jenis_pendidikan_en ?? '',
                ],
                [
                    'number' => '2.5',
                    'label' => 'Jenjang Kualifikasi Sesuai KKNI',
                    'label_en' => 'Level of Qualification in the National Qualification Framework',
                    'value' => $institution->level_kkni ?? '-',
                    'value_en' => $institution->level_kkni_en ?? '',
                ],
                [
                    'number' => '2.6',
                    'label' => 'Persyaratan Penerimaan',
                    'label_en' => 'Entry Requirements',
                    'value' => $institution->persyaratan_penerimaan ?? '-',
                    'value_en' => $institution->persyaratan_penerimaan_en ?? '',
                ],
                [
                    'number' => '2.7',
                    'label' => 'Bahasa Pengantar Kuliah',
                    'label_en' => 'Language of Instruction',
                    'value' => $institution->bahasa_pengantar ?? '-',
                    'value_en' => $institution->bahasa_pengantar_en ?? '',
                ],
                [
                    'number' => '2.8',
                    'label' => 'Sistem Penilaian',
                    'label_en' => 'Grading System',
                    'value' => $institution->sistem_penilaian ?? '-',
                    'value_en' => $institution->sistem_penilaian_en ?? '',
                ],
                [
                    'number' => '2.9',
                    'label' => 'Lama Studi Reguler',
                    'label_en' => 'Regular Length of Study',
                    'value' => $institution->lama_studi ?? '-',
                    'value_en' => $institution->lama_studi_en ?? '',
                ],
            ];
        @endphp
        <table class="personal-info">
            @foreach($institutionRows as $row)
                <tr>
                    <td class="number">{{ $row['number'] }}</td>
                    <td class="label">
                        {{ $row['label'] }}
                        @if(!empty($row['label_en']))
                            <span class="label-english">{{ $row['label_en'] }}</span>
                        @endif
                    </td>
                    <td class="value">
                        {!! $row['value'] ? nl2br(e($row['value'])) : '-' !!}
                        @if(!empty($row['value_en']))
                            <span class="value-english">{{ $row['value_en'] }}</span>
                        @endif
                        @if(!empty($row['value_secondary']))
                            <span class="value-neutral">{{ $row['value_secondary'] }}</span>
                        @endif
                        @if(!empty($row['value_secondary_en']))
                            <span class="value-english">{{ $row['value_secondary_en'] }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </section>

    <section>
        <div class="section-title">3. INFORMASI TENTANG KUALIFIKASI DAN HASIL YANG DICAPAI</div>
        <div class="section-subtitle">Information Identifying about the Qualification and Outcomes Obtained</div>
        @php
            $learningOutcomes = $document->learning_outcomes ?? [];
            $learningOutcomesEn = $document->learning_outcomes_en ?? [];

            $learningRows = [
                [
                    'title_id' => 'Penguasaan Pengetahuan',
                    'title_en' => 'Knowledge Competencies',
                    'content_id' => $learningOutcomes['knowledge'] ?? 'Memiliki penguasaan lintas disiplin sesuai kurikulum.',
                    'content_en' => $learningOutcomesEn['knowledge'] ?? 'Able to master interdisciplinary concepts in accordance with the curriculum.',
                ],
                [
                    'title_id' => 'Kemampuan Kerja',
                    'title_en' => 'Work Capability',
                    'content_id' => $learningOutcomes['skills'] ?? 'Mampu menjalankan tugas profesional dengan kualitas dan etika tinggi.',
                    'content_en' => $learningOutcomesEn['skills'] ?? 'Able to carry out professional duties with high quality and ethics.',
                ],
                [
                    'title_id' => 'Sikap Khusus',
                    'title_en' => 'Personal Attitudes',
                    'content_id' => $learningOutcomes['attitudes'] ?? 'Menunjukkan integritas, tanggung jawab, dan komunikasi yang efektif.',
                    'content_en' => $learningOutcomesEn['attitudes'] ?? 'Exhibits integrity, responsibility, and effective communication.',
                ],
            ];

            $degreeEnglish = config('skpi.document.degree_title_en');
            $kkniLevel = $institution->level_kkni ?? 'Level 6';
        @endphp
        <table class="learning-main-table">
            <tr>
                <td class="learning-letter">A</td>
                <td class="learning-title">
                    CAPAIAN PEMBELAJARAN
                    <span class="learning-subtext">
                        SARJANA PENDIDIKAN ISLAM
                        (KKNI LEVEL 6)
                    </span>
                </td>
                <td class="learning-program">
                    <div>LEARNING OUTCOMES</div>
                    <div class="learning-program-sub">
                        Bachelor of Islamic Education
                        (KKNI Level 6)
                    </div>
                </td>
            </tr>
        </table>
        <table class="learning-table">
            <thead>
                <tr>
                    <th class="learning-number">No.</th>
                    <th>Penguasaan Pengetahuan</th>
                    <th>Knowledge Competencies</th>
                </tr>
            </thead>
            <tbody>
                @foreach($learningRows as $index => $row)
                    <tr>
                        <td class="learning-number">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $row['title_id'] }}</strong>
                            @if(!empty($row['content_id']))
                                <p>{!! nl2br(e($row['content_id'])) !!}</p>
                            @endif
                        </td>
                        <td>
                            <strong class="learning-heading-english">{{ $row['title_en'] }}</strong>
                            @if(!empty($row['content_en']))
                                <p class="learning-content-english">{!! nl2br(e($row['content_en'])) !!}</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section>
        <table class="bilingual-table">
            <thead>
                <tr>
                    <th>Kemampuan Kerja</th>
                    <th class="english-column">Working Capability</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if(!empty($document->working_capability))
                            <ol class="bilingual-list">
                                @foreach($document->working_capability as $item)
                                    <li>{{ $item['id'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p>Tidak ada kemampuan kerja yang ditentukan.</p>
                        @endif
                    </td>
                    <td class="english-column">
                        @if(!empty($document->working_capability))
                            <ol class="bilingual-list">
                                @foreach($document->working_capability as $item)
                                    <li>{{ $item['en'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p>No work capability has been defined.</p>
                        @endif
                    </td>
                </tr>


            </tbody>
        </table>
        <table class="bilingual-table">
            <thead>
                <tr>
                    <th>Sikap Khusus</th>
                    <th class="english-column">Special Attitudes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if(!empty($document->special_attitude))
                            <ol class="bilingual-list">
                                @foreach($document->special_attitude as $item)
                                    <li>{{ $item['id'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p>Belum ada sikap khusus yang ditentukan.</p>
                        @endif
                    </td>
                    <td class="english-column">
                        @if(!empty($document->special_attitude))
                            <ol class="bilingual-list">
                                @foreach($document->special_attitude as $item)
                                    <li>{{ $item['en'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p>No special attitudes have been defined.</p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section>
        <div class="section-title">B. AKTIVITAS, PRESTASI DAN PENGHARGAAN</div>
        <div class="section-subtitle">Activities, Achievements, and Reward</div>
        <div class="activities-list">
            <ul>
                @forelse($activities as $activity)
                    <li>{{ $activity->nama_aktivitas }} ({{ ucwords(str_replace('_', ' ', $activity->jenis_aktivitas)) }}) – {{ $activity->tahun }}</li>
                @empty
                    <li>Tidak ada aktivitas yang disetujui.</li>
                @endforelse
            </ul>
        </div>
    </section>

    <section>
         <div class="section-title">4. KERANGKA KUALIFIKASI NASIONAL INDONESIA (KKNI)</div>
        <div class="section-subtitle">Indonesian National Qualification Framework (INQF)</div>
        <table class="bilingual-table">
            <thead>
                <tr>
                    <th>Kerangka Kualifikasi Nasional Indonesia (KKNI)</th>
                    <th class="english-column">Indonesian National Qualification Framework (INQF)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if(!empty($document->kkni_items))
                            <ol class="bilingual-list">
                                @foreach($document->kkni_items as $item)
                                    <li>{{ $item['id'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @elseif(!empty($document->kkni_text_id))
                            <p>{!! nl2br(e($document->kkni_text_id)) !!}</p>
                        @else
                            <p>Tidak ada informasi KKNI yang ditentukan.</p>
                        @endif
                    </td>
                    <td class="english-column">
                        @if(!empty($document->kkni_items))
                            <ol class="bilingual-list">
                                @foreach($document->kkni_items as $item)
                                    <li>{{ $item['en'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @elseif(!empty($document->kkni_text_en))
                            <p>{!! nl2br(e($document->kkni_text_en)) !!}</p>
                        @else
                            <p>No KKNI information has been provided.</p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section>
        <div class="section-title">5. PENGESAHAN SKPI</div>
        <div class="section-subtitle">SKPI Legalization</div>

        <div class="footer-signature">
            <div class="signature-box">
                <div class="signature-place">{{ $document->issued_place ?? 'Kota Tidak Diketahui' }}, {{ $document->issued_at?->format('d F Y') ?? '-' }}</div>
                @if(!empty($leader->signature_path))
                    <img src="{{ $leader->signature_path }}" alt="Tanda Tangan" class="signature-image">
                @else
                    <div class="signature-placeholder"></div>
                @endif
                <div class="signature-name">{{ $leader->name }}</div>
                <div class="signature-title">{{ $leader->title ?? 'Ketua STIT' }}</div>
                <div class="signature-nidn">NIDN: {{ $leader->nidn ?? '-' }}</div>
            </div>
        </div>
    </section>
    <div class="qr-corner">
        <div class="qr-image">
            @if(!empty($document->qr_code))
                <img src="data:image/png;base64,{{ $document->qr_code }}" alt="QR Code Verifikasi">
            @else
                <span>QR code belum tersedia</span>
            @endif
        </div>
    </div>
</div>
</body>
</html>
