<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes - {{ $book->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
        }

        .action-bar {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-outline {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        .page-container {
            width: 210mm;
            /* A4 width */
            min-height: 297mm;
            background: white;
            margin: 20px auto;
            padding: 15mm;
            box-sizing: border-box;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .barcode-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5mm;
        }

        .barcode-sticker {
            width: 55mm;
            height: 35mm;
            border: 1px dashed #9ca3af;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            padding: 4mm;
            page-break-inside: avoid;
        }

        .school-name {
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 3px;
            text-align: center;
            text-transform: uppercase;
        }

        .book-title {
            font-size: 6pt;
            margin-bottom: 5px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .barcode-img {
            margin-bottom: 2px;
        }

        .barcode-text {
            font-size: 7pt;
            font-family: monospace;
            letter-spacing: 1px;
            font-weight: bold;
        }

        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .page-container {
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                width: 100%;
                min-height: auto;
            }

            .barcode-sticker {
                /* If they use a dedicated barcode printer (e.g. Zebra), they would set the page size to the sticker size, 
                   and the grid layout would adapt or just print one per page if width is small */
            }

            @page {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="action-bar d-print-none">
        <div>
            <strong>{{ $book->title }}</strong> - Print Barcodes ({{ $book->copies->count() }} copies)
        </div>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline" style="margin-right: 10px;">
                &larr; Back
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                &#128438; Print Barcodes
            </button>
        </div>
    </div>

    <div class="page-container">
        <div class="barcode-grid">
            @foreach($book->copies as $copy)
                <div class="barcode-sticker">
                    <div class="school-name">{{ $setting->title ?? 'LIBRARY' }}</div>
                    <div class="book-title">{{ Str::limit($book->title, 30) }}</div>

                    <div class="barcode-img">
                        {!! \App\Services\QrCodeService::barcodeSvg($copy->barcode, 120, 40) !!}
                    </div>

                    <div class="barcode-text">{{ $copy->barcode }}</div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>