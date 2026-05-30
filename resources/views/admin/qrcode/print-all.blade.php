<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>All QR Codes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .qr-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; max-width: 900px; margin: 0 auto; }
        .qr-card { background: white; border-radius: 8px; padding: 10px; text-align: center; border: 1px solid #e5e7eb; }
        .qr-card img { width: 120px; height: 120px; }
        .qr-card h4 { font-size: 10px; color: #333; margin-top: 4px; }
        .qr-card .code { font-size: 9px; color: #f97316; font-weight: bold; }
        .qr-card .area { font-size: 8px; color: #888; }
        button { position: fixed; top: 10px; right: 10px; padding: 10px 20px; background: #f97316; color: white; border: none; border-radius: 8px; cursor: pointer; z-index: 999; }
        @media print {
            body { background: white; padding: 0; }
            button { display: none; }
            .qr-card { break-inside: avoid; border: 1px dashed #ccc; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()">Print All QR</button>
    <div class="qr-grid">
        @foreach($checkpoints as $cp)
        <div class="qr-card">
            @if($cp->qr_path)
            <img src="{{ asset('storage/' . $cp->qr_path) }}" alt="QR {{ $cp->code }}">
            @else
            <div style="width:120px;height:120px;background:#f3f4f6;margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No QR</div>
            @endif
            <h4>{{ $cp->name }}</h4>
            <div class="code">{{ $cp->code }}</div>
            <div class="area">{{ $cp->area?->name }}</div>
        </div>
        @endforeach
    </div>
</body>
</html>
