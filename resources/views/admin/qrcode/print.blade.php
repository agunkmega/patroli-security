<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>QR - {{ $checkpoint->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f5f5f5; }
        .qr-card { width: 200px; background: white; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .qr-card img { width: 160px; height: 160px; margin-bottom: 8px; }
        .qr-card h3 { font-size: 11px; color: #333; margin-bottom: 2px; }
        .qr-card p { font-size: 8px; color: #666; }
        .qr-card .code { font-size: 10px; color: #f97316; font-weight: bold; margin-top: 4px; }
        .print-area { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding: 16px; }
        @media print {
            body { background: white; }
            .no-print { display: none; }
            .qr-card { box-shadow: none; border: 1px dashed #ccc; break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position:fixed;top:10px;left:50%;transform:translateX(-50%);z-index:999;text-align:center;">
        <button onclick="window.print()" style="padding:10px 24px;background:#f97316;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;margin-bottom:10px;">Print QR Code</button>
        <br>
        <a href="{{ route('admin.checkpoints.index') }}" style="color:#666;font-size:12px;">Kembali</a>
    </div>

    <div class="print-area">
        @for($i = 0; $i < 8; $i++)
        <div class="qr-card">
            @if($checkpoint->qr_path)
            <img src="{{ asset('storage/' . $checkpoint->qr_path) }}" alt="QR">
            @endif
            <h3>{{ $checkpoint->name }}</h3>
            <p>{{ $checkpoint->area?->name }}</p>
            <div class="code">{{ $checkpoint->code }}</div>
        </div>
        @endfor
    </div>
</body>
</html>
