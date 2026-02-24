<div id="lampiran-skp" style="max-height: 3500px;min-height: 900px;break-inside: avoid;display: table;" class="page-break-after">
    {{-- Tabel Judul (Header) --}}
    <table class="table-fs-11">
        <tbody>
            <tr>
                <td colspan="2">
                    <center><img src="./logo-jabar.png" alt="" width="75" ></center>
                </td>
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
    <table class="table-fs-11">
        <tbody>
            <tr>
                <td colspan="6" style="text-align: justify;text-justify: inter-word;text-indent:12.5%;">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan, dan akuntabel serta berorientasi pada hasil, yang bertandatangan di bawah ini :</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</;</td>
            </tr>
            <tr>
                <td style="text-indent: 60%;">Nama</td>
                <td style="text-align: left;" width="10px">:</td>
                <td colspan="4" style="text-align: left;text-indent:5%;">{{ $pegawai->peg_nama }}</td>
            </tr>
            <tr>
            <tr>
                <td style="text-indent: 60%;">Jabatan</td>
                <td style="text-align: left;">:</td>
                <td  colspan="4" style="text-align: left;text-indent:5%;">{{ $pegawai->jabatan_nama }}</td>
            </tr>
                <td colspan="6" style="text-indent: 12.5%;">Selanjutnya disebut PIHAK KESATU</td>
            </tr>
            <tr>
                <td style="text-indent: 60%;">Nama</td>
                <td style="text-align: left;">:</td>
                <td colspan="4" style="text-align: left;text-indent:5%;">{{ $atasan->peg_nama }}</td>
            </tr>
            <tr>
            <tr>
                <td style="text-indent: 60%;">Jabatan</td>
                <td style="text-align: left;">:</td>
                <td colspan="4" style="text-align: left;text-indent:5%;">{{ $atasan->jabatan_nama }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-indent: 12.5%;">Selaku atasan PIHAK KESATU, selanjutnya disebut PIHAK KEDUA</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</;</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: justify;text-justify: inter-word;text-indent:12.5%;">PIHAK KESATU berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah yang dilaksanakan dalam program dan kegiatan seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab kami.</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</;</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: justify;text-justify: inter-word;text-indent:12.5%;">PIHAK KEDUA akan melakukan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.</td>
            </tr>

        </tbody>
    </table>


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
