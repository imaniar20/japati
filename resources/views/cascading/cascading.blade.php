<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php 
// \Log::debug($data[0]['sasaran_strategis_pd']);
$index = 1;
foreach ($data as $item){
    foreach($item->sasaranStrategisPd as $spd){
        $spd->merge = 0;
        $spd->merge++;
        foreach($spd->kinerjaProgram as $ikp => $kp){
            $spd->merge++;
            $kp->merge = 0;
            $kp->merge++;
            foreach($kp->kinerjaKegiatan as $ikk => $kk){
                $spd->merge++;
                $kp->merge++;
                $kk->merge=0;
                $kk->merge++;
                foreach($kk->kinerjaSubKegiatan as $iksk => $ksk){
                    $spd->merge++;
                    $kp->merge++;
                    $kk->merge++;
                    $ksk->merge=0;
                    $ksk->merge++;
                }
            }
        }
    }
}
?>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tujuan PD</th>
                <th>Indikator Tujuan PD</th>
                <th>Target 2025</th>
                <th>Sasaran PD</th>
                <th>Indikator Sasaran PD</th>
                <th>Satuan</th>
                <th>Target 2025</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Sub Kegiatan</th>
                <th>Indikator Kinerja Program/Kegiatan/subKegiatan</th>
                <th>Satuan</th>
                <th>Target 2025</th>
                <th>Keterangan</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                @foreach($item->sasaranStrategisPd as $spd)
                    <tr>
                        <td rowspan="{{$spd->merge}}">{{$index++}}</td>
                        <td rowspan="{{$spd->merge}}">{{$item->tujuan->tujuan}}</td>
                        <td rowspan="{{$spd->merge}}">{{$item->indikatorSasaranStrategis->indikator ?? '-'}}</td>
                        <td rowspan="{{$spd->merge}}">{{$item->target ?? '-'}}</td>
                        <td rowspan="{{$spd->merge}}">{{$spd->sasaran_strategis_satker ?? '-'}}</td>
                        <td rowspan="{{$spd->merge}}">{{$spd->iku ?? '-'}}</td>
                        <td rowspan="{{$spd->merge}}">{{$spd->satuan ?? '-'}}</td>
                        <td rowspan="{{$spd->merge}}">{{$spd->target ?? '-'}}</td>
                        <td>{{'-'}}</td>
                        <td>{{'-'}}</td>
                        <td>{{'-'}}</td>
                        <td>{{'-'}}</td>
                        <td>{{'-'}}</td>
                        <td>-</td>
                    </tr>
                    @foreach($spd->kinerjaProgram as $ikp => $kp)
                        <tr>
                            
                            <td rowspan="{{$kp->merge}}">{{$kp->program->nama ?? '-'}}</td>
                            <td>{{'-'}}</td>
                            <td>{{'-'}}</td>
                            <td>{{$kp->indikator ?? '-'}}</td>
                            <td>{{$kp->satuan ?? '-'}}</td>
                            <td>{{$kp->target ?? '-'}}</td>
                            <td>-</td>
                        </tr>
                        @foreach($kp->kinerjaKegiatan as $ikk => $kk)
                            <tr>

                                <td rowspan="{{$kk->merge}}">{{$kk->kegiatan->nama ?? '-'}}</td>
                                <td>{{'-'}}</td>
                                <td>{{$kk->indikator ?? '-'}}</td>
                                <td>{{$kk->satuan ?? '-'}}</td>
                                <td>{{$kk->target ?? '-'}}</td>
                                <td>-</td>
                            </tr>
                            @foreach($kk->kinerjaSubKegiatan as $iksk => $ksk)
                                <tr>

                                    <td rowspan="{{$ksk->merge}}">{{$ksk->subKegiatan->nama ?? '-'}}</td>
                                    <td>{{$ksk->indikator ?? '-'}}</td>
                                    <td>{{$ksk->satuan ?? '-'}}</td>
                                    <td>{{$ksk->target ?? '-'}}</td>
                                    <td>-</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>