<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>Rank</th>
                <th>PD</th>
                <th>Capaian Kinerja</th>
                <th>Efisiensi</th>
                <th>Jumlah Anggaran</th>
                <th>Jumlah yang Terserap</th>
                <th>Jumlah yg tidak terserap</th>
                <th>% terserap</th>
                <th>% tdk terserap</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->rank }}</td>
                    <td>{{ $item->satuan_kerja_nama }}</td>
                    <td>{{ $item->capaian }}</td>
                    <td>{{ $item->efisiensi_anggaran }}</td>
                    <td>{{ $item->anggaran }}</td>
                    <td>{{ $item->anggaran_terserap }}</td>
                    <td>{{ $item->anggaran_tidak_terserap }}</td>
                    <td>{{ $item->anggaran ? (($item->anggaran_terserap / $item->anggaran) * 100) : 0 }}</td>
                    <td>{{ $item->anggaran ? (($item->anggaran_tidak_terserap / $item->anggaran) * 100) : 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>