<div id="lampiran-skp" style="max-height: 3500px;min-height: 900px;break-inside: avoid;display: table;">
    {{-- Tabel Judul (Header) --}}
    <table class="table-fs-11">
        <tbody>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="title-2">PERJANJIAN KINERJA TAHUN {{$tahun}}</td>
            </tr>
            <tr>
                <td colspan="2" class="title-2">{{ $pegawai->jabatan_nama}}</td>
            </tr>
            <tr>
                <td colspan="2" class="title-2">PROVINSI JAWA BARAT</td>
            </tr>
            <tr>
                <td colspan="2" class="title-2">PEMERINTAH DAERAH PROVINSI JAWA BARAT</td>
            </tr>
        </tbody>
    </table>

    <br>

    {{-- Tabel SKP --}}
    <table class="table-fs-11" border="1" class="italic">
        <thead>
            <th> No. </th>
            <th> Sasaran Kinerja </th>
            <th> Indikator Kinerja </th>
            <th> Target </th>
        </thead>
        <tbody>
            @forelse ($sasaran as $skp)
                <tr>
                    <td width="5%" class="text-center">{{ $loop->iteration }}</td>
                    <td class="pl-3">{{ $skp->sasaran }}</td>
                    <td class="pl-3">{{ $skp->indikator }}</td>
                    <td class="pl-3">{{ $skp->target }} {{ $skp->satuan }}</td>
                </tr>
            @empty
                <tr>
                    <td width="5%" class="text-center">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    @if(count($program_anggaran) > 0)
    <table class="table-fs-11" border="1" class="italic">
        <thead>
            <th> No. </th>
            <th> {{$program_anggaran[0]->jenis}} </th>
            <th> Anggaran </th>
            <th> Keterangan </th>
        </thead>
        <tbody>
            @forelse ($program_anggaran as $anggaran)
                <tr>
                    <td width="5%" height="20pt" class="text-center">{{ $loop->iteration }}</td>
                    <td class="pl-3">{{ $anggaran->sasaran }}</td>
                    <td class="pl-3">{{ 'Rp.' . number_format($anggaran->anggaran, 2) }}</td>
                    <td class="pl-3">APBD</td>
                </tr>
            @empty
                <tr>
                    <td width="5%" height="20pt" class="text-center">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                    <td class="pl-3">&nbsp;</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif


    {{-- Tabel Tandatangan --}}
    <table class="table-fs-11 text-center" style="margin-top: 20px;">
        <tbody>
            <tr>
                <td width="50%" rowspan="2" class="text-bottom">PIHAK KEDUA</td>
                <td>Bandung, {{$tanggal}}</td>
            </tr>
            <tr>
                <td>PIHAK KESATU</td>
            </tr>
            <tr>
                <td width="50%" rowspan="2" class="text-bottom">{{ $atasan->jabatan_nama }}</td>
            </tr>
            <tr>
                <td>{{ $pegawai->jabatan_nama }}</td>
            </tr>
            <tr>
                <td style="height: 90pt; position: relative; padding: 20px;">
                    <div style="border-radius: 20px; overflow: hidden; border: 1px solid black; border-collapse: separate;">  
                        <table style="width: 90%; border: none; margin: auto; border-spacing: 0;"> 
                            <tr>
                                <td style="width: 25%; padding: 10px; vertical-align: top;"> 
                                    <!-- Image Cell -->
                                    <div style="display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ public_path('logo-jabar-spesimen.png') }}" alt="" width="75">
                                    </div>
                                </td>
                                <td style="width: 75%; padding: 10px; word-wrap: break-word; vertical-align: top;">
                                    <!-- Text Cell -->
                                    <div style="text-align: left;">
                                        <p style="font-size: 10px;">
                                            Ditandatangani secara elektronik oleh :<br>
                                            {{ $atasan->jabatan_nama }}<br>
                                        </p>
                                        <br>
                                        <br>
                                        <p style="font-size: 10px;">
                                            {{ $atasan->peg_nama }}<br>
                                            {{ $atasan->nm_pkt_akhir.', '.$atasan->nm_gol_akhir }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>

                <td style="height: 90pt; position: relative; padding: 20px;">
                    <div style="border-radius: 20px; overflow: hidden; border: 1px solid black; border-collapse: separate;">
                        <table style="width: 90%; border: none; margin: auto; border-spacing: 0;"> 
                            <tr>
                                <td style="width: 25%; padding: 10px; vertical-align: top;">
                                    <!-- Image Cell -->
                                    <div style="display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ public_path('logo-jabar-spesimen.png') }}" alt="" width="75">
                                    </div>
                                </td>
                                <td style="width: 75%; padding: 10px; word-wrap: break-word; vertical-align: top;">
                                    <!-- Text Cell -->
                                    <div style="text-align: left;">
                                        <p style="font-size: 10px;">
                                            Ditandatangani secara elektronik oleh :<br>
                                            {{ $pegawai->jabatan_nama }}<br>
                                        </p>
                                        <br>
                                        <br>
                                        <p style="font-size: 10px;">
                                            {{ $pegawai->peg_nama }}<br>
                                            {{ $pegawai->nm_pkt_akhir.', '.$pegawai->nm_gol_akhir }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
