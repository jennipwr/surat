<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Surat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: #f0f4f8;
            padding-top: 50px;
        }
        .container {
            max-width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .iframe-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .iframe-container iframe {
            border-radius: 8px;
        }
        h4 {
            font-size: 1.5rem;
            color: #333;
        }
        .btn-preview {
            background-color: #0069d9;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1rem;
        }
        .btn-preview:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h4 class="mb-4">Preview Surat: {{ $surat->file }}</h4>

    <div class="iframe-container">
        <iframe src="{{ route('surat.preview', $surat->id_surat) }}" width="100%" height="700px"></iframe>
    </div>

    <div class="mt-4">
        @if (Auth::check() && Auth::user()->role === 'mahasiswa')
            <a class="sidebar-link btn-preview" href="{{ route('mahasiswa.surat.download', $surat->id_surat) }}" aria-expanded="false">
                <i class="bi bi-eye"></i> Download Surat
            </a>
        @elseif (Auth::check() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'tu')
            <a class="sidebar-link btn-preview" href="{{ route('tu.surat.download', $surat->id_surat) }}" aria-expanded="false">
                <i class="bi bi-eye"></i> Download Surat
            </a>
        @elseif (Auth::check() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'kaprodi')
            <a class="sidebar-link btn-preview" href="{{ route('kaprodi.surat.download', $surat->id_surat) }}" aria-expanded="false">
                <i class="bi bi-eye"></i> Download Surat
            </a>
        @endif
    </div>
</div>

<div class="footer">
    <p>&copy; 2372002 2372010 - Jennifer Marco - All rights reserved</p>
</div>

</body>
</html>
