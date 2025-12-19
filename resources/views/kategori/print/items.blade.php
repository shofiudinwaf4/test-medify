<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Item per Kategori</title>

    <style>
        @page {
            margin: 40px 30px 70px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .sub-header {
            text-align: start;
            font-size: 12px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            height: 45px;
            text-align: center;
            font-size: 10px;
            color: #000;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <h2>DAFTAR ITEM</h2>
    <div class="sub-header">
        <strong>Kode Kategori:</strong> {{ $kategori->kode_kategori }} <br>
        <strong>Nama Kategori:</strong> {{ $kategori->nama_kategori }}
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:25%">Nama Item</th>
                <th style="width:12%">Harga Beli</th>
                <th style="width:8%">Laba (%)</th>
                <th style="width:12%">Harga Jual</th>
                <th style="width:18%">Supplier</th>
                <th style="width:10%">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategori->items as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-center">
                        {{ number_format($item->harga_beli, 0, ',', '.') }}
                    </td>
                    <td class="text-center">{{ $item->laba }}</td>
                    <td class="text-center">
                        {{ number_format($item->harga_beli + ($item->harga_beli * $item->laba) / 100, 0, ',', '.') }}
                    </td>
                    <td>{{ $item->supplier }}</td>
                    <td class="text-center">{{ $item->jenis }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada item pada kategori ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <footer>
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </footer>

    {{-- PAGE NUMBER --}}
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(
                30,
                820,
                "Dicetak pada {{ date('d/m/Y H:i') }} | Halaman {PAGE_NUM} dari {PAGE_COUNT}",
                null,
                9
            );
        }
    </script>

</body>

</html>
