<template>
  <div style="font-family: 'Intro', sans-serif;" class="pos-r">
    <b-button v-if="showDiagram" @click="renderImage" variant="primary" :disabled="isBusy.render">
      <b-spinner small v-if="isBusy.render"></b-spinner>
      Render gambar
    </b-button>
    <b-button v-if="showDiagram" variant="success" @click="exportDiagramImage(satker ? satker.satuan_kerja_nama : '')" :disabled="isBusy.export">
      <b-spinner small v-if="isBusy.export"></b-spinner>
      <i v-else class="fa fa-image"></i>
      Export
    </b-button>
    <b-button v-if="showDiagram" variant="success" @click="exportExcel(satker ? satker.satuan_kerja_nama : '')" :disabled="isBusy.exportExcel">
      <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
      <i v-else class="fa fa-file-excel-o"></i>
      Export Excel
    </b-button>
    <b-form-checkbox v-if="showSwitchIconCapaian && showDiagram" v-model="options.showIconCapaian" name="check-icon" switch class="mt-2">
      Tampilkan icon capaian
    </b-form-checkbox>
    <b-form-checkbox
      v-if="showSwitchIconEditKinerja && showDiagram"
      v-model="options.showIconEditKinerja"
      name="check-icon"
      switch
      class="mt-2"
    >
      Tampilkan icon edit kinerja
    </b-form-checkbox>
    <b-form-checkbox v-if="showDiagram" v-model="options.showCross" name="check-icon" switch class="mt-2">
      Tampilkan cross cutting
    </b-form-checkbox>
    <b-alert show class="mt-1" v-if="$route.query.showOption == 'true'">
      <b-form-group label="Pixel Ratio" label-for="pixel-ratio">
        <b-form-input id="pixel-ratio" v-model="options.export.pixelRatio" type="number" step="0.1"></b-form-input>
      </b-form-group>
    </b-alert>

    <b-form-checkbox v-if="renderedImage" v-model="showDiagram" name="show-diagram" switch class="mt-2">
      Tampilkan dalam bentuk teks
    </b-form-checkbox>
    <div v-if="isBusy.render" class="text-center">
      <b-spinner small></b-spinner>
      Sedang merender data...
    </div>
    <button class="maximize-btn btn btn-light" type="button" @click="toggle" v-if="!fullscreen"><i class="fa fa-arrows-alt"></i> Maximize</button>

    <div :key="keyComponent" class="fullscreen-wrapper" style="overflow-x:hidden; z-index:1000; background:white;" :style="{'overflow-y': (showDiagram ? 'scroll' : 'hidden')}">
      <VuePanZoom :options="{minZoom: 1, maxZoom: 40}" v-show="renderedImage ? !showDiagram : true">
        <img id="img-diagram" :src="renderedImage" style="max-width:100%" alt="">
      </VuePanZoom>
      <button class="minimize-btn btn btn-light" type="button" v-if="fullscreen" @click="toggle" ><i class="fa fa-window-close-o"></i> Minimize</button>

      <HandyScroll v-if="showDiagram">
        <div id="diagram" class="justify-content-center" style="background:white; display:inline-flex; flex-direction: column;padding:0 15px 0 30px; margin: 15px; padding-bottom:50px;">
          <div class="text-center mb-3 h4"><span v-if="satker">{{ satker.satuan_kerja_nama }}</span></div>
          <div class="text-left align-items-center" style="display: inline-flex;flex-direction: column;">
            <ul class="tree m-0">
              <!-- level 1 - IKU Gubernur -->
              <li v-for="sasaranRpjmd of data" :key="sasaranRpjmd.id">
                <span class="text-left green-box sasaranRpjmd">
                  <div class="text-right w-100">
                    <template v-if="options.showIconCapaian">
                      <i v-if="parseIconCapaianColor(sasaranRpjmd.capaian, sasaranRpjmd.kinerja_tidak_tercapai.length).status" v-b-tooltip.hover :title="parseIconCapaianColor(sasaranRpjmd.capaian, sasaranRpjmd.kinerja_tidak_tercapai.length).title" @click="showKegagalan(sasaranRpjmd)" class="fa-stack" style="cursor:pointer">
                        <i class="fa fa-circle fa-stack-2x text-white"></i>
                        <i class="fa fa-times fa-stack-1x" :class="parseIconCapaianColor(sasaranRpjmd.capaian, sasaranRpjmd.kinerja_tidak_tercapai.length).class"></i>
                      </i>
                    </template>
                    <b-button
                      @click="$nuxt.$emit('show-modal-rencana-aksi-gubernur', satkerId, sasaranRpjmd.id, sasaranRpjmd.indikator_sasaran_strategis.indikator, sasaranRpjmd.sasaran_strategis.sasaran)"
                      variant="primary"
                      size="sm"
                      pill
                      v-b-tooltip.hover
                      title="Rencana Aksi"
                    >
                      RA
                    </b-button>
                    <b-button
                      @click="$nuxt.$emit('show-modal-realisasi-rencana-aksi-gubernur', satkerId, sasaranRpjmd.id, sasaranRpjmd.indikator_sasaran_strategis.indikator, sasaranRpjmd.sasaran_strategis.sasaran)"
                      variant="primary"
                      size="sm"
                      pill
                      v-b-tooltip.hover
                      title="Realisasi Rencana Aksi"
                    >
                      RRA
                    </b-button>
                    <b-button
                      v-if="sasaranRpjmd.sasaran_strategis_pd_cross && sasaranRpjmd.sasaran_strategis_pd_cross.length"
                      @click="toggleShowCrossId('sasaranRpjmd', sasaranRpjmd.id); keyComponent++"
                      :variant="isShowCrossId('sasaranRpjmd', sasaranRpjmd.id) ? 'primary' : 'secondary'"
                      size="sm"
                      pill
                      v-b-tooltip.hover
                      title="Tampilkan cross cutting"
                    >
                      CC
                    </b-button>
                     <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-why',
                                 satkerId,
                                 sasaranRpjmd.id
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Why"
                            class="mr-1"
                          >
                            W
                          </b-button>
                    <b-button  class="mr-1"
                      v-if="options.showIconEditKinerja"
                        @click="
                          onAddKinerjaSasaranStrategisPd (
                            'sasaranStrategisPd',  sasaranRpjmd.sasaran_strategis_id, sasaranRpjmd.id, sasaranRpjmd.indikator_sasaran_strategis_id
                          )
                        "
                        variant="dark"
                        size="sm"
                        pill
                        v-b-tooltip.hover
                        title="Tambah Sasaran Strategis Perangkat Daerah"
                      >
                        <i
                          class="fa fa-plus"
                          aria-hidden="true"
                        ></i>
                      </b-button>
                     
                  </div>
                  <div class="d-flex align-items-baseline w-100">
                    <i class="fa fa-bullseye mr-2"></i>
                    <div>
                      <small>{{ labels.lv1.sasaran }}:</small>
                      <h6 class="m-0 mb-1 sasaran-title font-wight-bold" style="word-wrap: anywhere;">{{ sasaranRpjmd.sasaran_strategis.sasaran }}</h6>
                    </div>
                  </div>
                  <div v-if="visibility.indikator" class="mt-2 d-flex align-items-baseline w-100">
                    <i class="fa fa-tachometer mr-2"></i>
                    <div>
                      <small>{{ labels.lv1.indikator }}:</small>
                      <template v-if="sasaranRpjmd.indikator_merge && sasaranRpjmd.indikator_merge.length">
                        <h6 v-for="(indikator, indikatorIndex) in sasaranRpjmd.indikator_merge" :key="indikatorIndex" class="m-0  font-wight-bold" style="word-wrap: anywhere;">({{ indikator }})</h6>
                      </template>
                      <h6 v-else class="m-0  font-wight-bold" style="word-wrap: anywhere;">({{ sasaranRpjmd.indikator_sasaran_strategis.indikator }})</h6>
                    </div>
                  </div>
                  <div v-if="visibility.pengampu" class="mt-2 indikator d-flex align-items-baseline w-100">
                    <i class="fa fa-user mr-2"></i>
                    <div>
                      <small>Pengampu:  </small>
                      <h6 class="m-0 mb-1 sasaran-title" style="word-wrap: anywhere;"> Bupati</h6>
                    </div>
                  </div>
                </span>
                <!-- level 2 - IKU OPD -->
                <ul v-if="sasaranRpjmd.sasaran_strategis_pd && sasaranRpjmd.sasaran_strategis_pd.length">
                  <template v-for="satkerSasaran of mergeSasaranStrategisPd(sasaranRpjmd.id, sasaranRpjmd.sasaran_strategis_pd, sasaranRpjmd.sasaran_strategis_pd_cross)">
                    <li :key="`${satkerSasaran.id}-${satkerSasaran.is_cross}`" v-if="showBayangan ? satkerSasaran.render_status != false : true" :class="{cross: satkerSasaran.is_cross, 'cross-exists': satkerSasaran.sasaran_strategis_pd_cross_exists}">
                      <span class="text-left satkerSasaran yellow-box" :class="{'d-none': showBayangan && satkerSasaran.render_status == 'hide'}">
                        <div v-if="!satkerSasaran.is_cross && showBayangan && satkerSasaran.kinerja_bayangan" class="mb-2 kinerja-bayangan-container p-5 rounded-lg" style="padding-left:10px !important; margin-top: -7px;width: calc(100% + 20px)">
                          <div class="d-flex align-items-baseline w-100">
                            <i class="fa fa-bullseye mr-2"></i>
                            <div>
                              <small>{{ labels.lv2.sasaran }}:</small>
                              <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ satkerSasaran.kinerja_bayangan.sasaran }}</h6>
                            </div>
                          </div>
                          <div class="mt-2 d-flex align-items-baseline w-100">
                            <i class="fa fa-tachometer mr-2"></i>
                            <div>
                              <small>{{ labels.lv2.indikator }}:</small>
                              <h6 class="m-0  font-weight-bold" style="word-wrap: anywhere;">({{ satkerSasaran.kinerja_bayangan.indikator }})</h6>
                            </div>
                          </div>
                        </div>
                        <small v-if="!satkerSasaran.is_cross && $route.query.showSkp == 'true' && satkerSasaran.skp && satkerSasaran.skp.length" class="text-white">Ditandai sebagai SKP</small>
                        <small v-if="!satkerSasaran.is_cross && $route.query.showSkp == 'true' && (!satkerSasaran.skp ||!satkerSasaran.skp.length)" class="text-white">Bukan SKP</small>
                        <div v-if="!satkerSasaran.is_cross" class="text-right w-100">
                          <template v-if="options.showIconCapaian">
                            <i v-if="parseIconCapaianColor(satkerSasaran.capaian, satkerSasaran.kinerja_tidak_tercapai.length).status" v-b-tooltip.hover :title="parseIconCapaianColor(satkerSasaran.capaian, satkerSasaran.kinerja_tidak_tercapai.length).title" @click="showKegagalan(satkerSasaran)" class="fa-stack" style="cursor:pointer">
                              <i class="fa fa-circle fa-stack-2x text-white"></i>
                              <i class="fa fa-times fa-stack-1x" :class="parseIconCapaianColor(satkerSasaran.capaian, satkerSasaran.kinerja_tidak_tercapai.length).class"></i>
                            </i>
                          </template>

                          <a v-if="satkerSasaran.definisi_operasional" :href="satkerSasaran.definisi_operasional" target="_blank" v-b-tooltip.hover title="Definisi Operasional" class="btn btn-primary btn-sm rounded-pill">
                            DO
                          </a>
                          <b-button v-if="satkerSasaran.do_rumus"
                            @click="
                              $nuxt.$emit(
                                'show-modal-definisi-operasional',
                                satkerId,
                                satkerSasaran.id,
                                satkerSasaran.iku
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Definisi Operasional Sasaran Strategis"
                          >
                            DO
                          </b-button>
                          <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-why',
                                satkerId,
                                sasaranRpjmd.id,
                                satkerSasaran.id,
                                satkerSasaran.iku
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Why"
                          >
                            W
                          </b-button>
                          <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-berbagi-peran',
                                satkerSasaran.sasaran_strategis_satker,
                                satkerSasaran.id,
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Berbagi Peran"
                          >
                            BP
                          </b-button>
                          <b-button
                            @click="$nuxt.$emit('show-modal-rencana-aksi', satkerId, satkerSasaran.id, satkerSasaran.iku, satkerSasaran.sasaran_strategis_satker )"
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Rencana Aksi"
                            :id="`rencana-aksi-${satkerSasaran.id}-btn`"
                            :class="{highlight: autoShowModal.highlight == `rencana-aksi-${satkerSasaran.id}`}"
                          >
                            RA
                          </b-button>
                          <b-button
                            @click="$nuxt.$emit('show-modal-realisasi-rencana-aksi', satkerId, satkerSasaran.id, satkerSasaran.iku, satkerSasaran.sasaran_strategis_satker)"
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Realisasi Rencana Aksi"
                            :id="`realisasi-rencana-aksi-${satkerSasaran.id}-btn`"
                            :class="{highlight: autoShowModal.highlight == `realisasi-rencana-aksi-${satkerSasaran.id}`}"
                          >
                            RRA
                          </b-button>
                           <b-button
                                  v-if="options.showIconEditKinerja"
                                  @click="
                                    onEditKinerja(
                                      'sasaranStrategisPd',
                                      satkerSasaran.id
                                    )
                                  "
                                  variant="secondary"
                                  size="sm"
                                  pill
                                  v-b-tooltip.hover
                                  title="Edit Kinerja Sasaran Strategis PD"
                                >
                                  <i class="fa fa-edit" aria-hidden="true"></i>
                                </b-button>
                                <b-button
                                  v-if="options.showIconEditKinerja"
                                    @click="
                                      onAddKinerja(
                                        'kinerjaProgram',  satkerSasaran.id
                                      )
                                    " variant="dark"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    title="Tambah Kinerja Program">
                                    <i
                                      class="fa fa-plus"
                                      aria-hidden="true"
                                    ></i>
                                  </b-button>
                          <b-button
                            v-if="satkerSasaran.kinerja_program_cross && satkerSasaran.kinerja_program_cross.length"
                            @click="toggleShowCrossId('satkerSasaran', satkerSasaran.id); keyComponent++"
                            :variant="isShowCrossId('satkerSasaran', satkerSasaran.id) ? 'primary' : 'secondary'"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Tampilkan cross cutting"
                          >
                            CC
                          </b-button>
                          <!-- <b-button
                           v-if="satkerSasaran.do_rumus"
                            @click="
                              $nuxt.$emit(
                                'show-modal-definisi-operasional',
                                satkerId,
                                satkerSasaran.id,
                                satkerSasaran.iku
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Definisi Operasional Sasaran Strategis"
                          >
                            DO
                          </b-button> -->
                        </div>
                        <div v-if="satkerSasaran.is_cross" class="mb-2 d-flex align-items-baseline w-100">
                          <i class="fa fa-sitemap mr-2"></i>
                          <div>
                            <small>Satuan Kerja:</small>
                            <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ satkerSasaran.satuan_kerja.satuan_kerja_nama }}</h6>
                          </div>
                        </div>
                        <div class="d-flex align-items-baseline w-100">
                          <i class="fa fa-bullseye mr-2"></i>
                          <div>
                            <small>{{ labels.lv2.sasaran }}:</small>
                            <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ satkerSasaran.sasaran_strategis_satker }}</h6>
                          </div>
                        </div>
                        <div v-if="visibility.indikator" class="mt-2 d-flex align-items-baseline w-100">
                          <i class="fa fa-tachometer mr-2"></i>
                          <div>
                            <small>{{ labels.lv2.indikator }}:</small>
                            <template v-if="satkerSasaran.indikator_merge && satkerSasaran.indikator_merge.length">
                              <h6 v-for="(indikator, indikatorIndex) in satkerSasaran.indikator_merge" :key="indikatorIndex" class="m-0 font-weight-bold" style="word-wrap: anywhere;">({{ indikator }})</h6>
                            </template>
                            <h6 v-else class="m-0 font-weight-bold" style="word-wrap: anywhere;">({{ satkerSasaran.iku }})</h6>
                          </div>
                        </div>
                        <div v-if="visibility.pengampu" class="mt-2 indikator d-flex align-items-baseline w-100">
                          <i class="fa fa-user mr-2"></i>
                          <div>
                            <small>Pengampu:  </small>
                            <h6 class="m-0 mb-1 sasaran-title" style="word-wrap: anywhere;">Kepala {{ satkerSasaran.satuan_kerja?.satuan_kerja_nama || '-' }}</h6>
                          </div>
                        </div>
                      </span>
                      <!-- level 3 - Kinerja Program -->
                      <ul v-if="maxLevel >= 3 && satkerSasaran.kinerja_program && satkerSasaran.kinerja_program.length">
                        <template v-for="kinerjaProgram of mergeKinerjaProgram(satkerSasaran.id, satkerSasaran.kinerja_program, satkerSasaran.kinerja_program_cross)">
                        <li :key="`${kinerjaProgram.id}-${kinerjaProgram.is_cross}`" 
    v-if="((showBayangan ? satkerSasaran.render_status != false && kinerjaProgram.render_status != false : true) && kinerjaProgram.need_to_remove != true)"
    :class="{cross: kinerjaProgram.is_cross, 'cross-exists': kinerjaProgram.kinerja_program_cross_exists}">
                            <span class="text-left kinerjaProgram blue-box" :class="{'d-none': showBayangan && kinerjaProgram.render_status == 'hide'}">
                              <div v-if="!kinerjaProgram.is_cross && showBayangan && kinerjaProgram.kinerja_bayangan" class="mb-2 kinerja-bayangan-container p-5 rounded-lg" style="padding-left:10px !important; margin-top: -7px;width: calc(100% + 20px)">
                                <div class="d-flex align-items-baseline w-100">
                                  <i class="fa fa-bullseye mr-2"></i>
                                  <div>
                                    <small>{{ labels.lv3.sasaran }}:</small>
                                    <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaProgram.kinerja_bayangan.sasaran }}</h6>
                                  </div>
                                </div>
                                <div class="mt-2 d-flex align-items-baseline w-100">
                                  <i class="fa fa-tachometer mr-2"></i>
                                  <div>
                                    
                                    <small>{{ labels.lv3.indikator }}:</small>
                                    <h6 class="m-0  font-weight-bold" style="word-wrap: anywhere;">({{ kinerjaProgram.kinerja_bayangan.indikator }})</h6>
                                  </div>
                                </div>
                              </div>
                              <small v-if="!kinerjaProgram.is_cross && $route.query.showSkp == 'true' && kinerjaProgram.skp && kinerjaProgram.skp.length" class="text-white">Ditandai sebagai SKP</small>
                              <small v-if="!kinerjaProgram.is_cross && $route.query.showSkp == 'true' && (!kinerjaProgram.skp || !kinerjaProgram.skp.length)" class="text-white">Bukan SKP</small>
                              <div v-if="!kinerjaProgram.is_cross" class="d-flex  justify-content-between mb-2">
                                <template v-if="options.showIconCapaian">
                                  <div>
                                    <i v-if="kinerjaProgram.solusi" @click="$refs['modal-solusi-masalah'].showModal('kinerja-program', kinerjaProgram.id)" class="fa-stack" v-b-tooltip.hover title="Solusi Kinerja" style="cursor:pointer">
                                      <i class="fa fa-circle fa-stack-2x text-white"></i>
                                      <i class="fa fa-check text-success fa-stack-1x"></i>
                                    </i>
                                  </div>
                                  <div>
                                    <i v-if="kinerjaProgram.kinerja_tercapai.length" @click="showKinerjaTercapaiModal(kinerjaProgram.kinerja_tercapai)" class="fa-stack" v-b-tooltip.hover title="CSF" style="cursor:pointer">
                                      <i class="fa fa-circle fa-stack-2x text-white"></i>
                                      <i class="fa fa-info text-info fa-stack-1x"></i>
                                    </i>
                                    <i v-if="parseIconCapaianColor(kinerjaProgram.capaian, kinerjaProgram.kinerja_tidak_tercapai.length).status" v-b-tooltip.hover :title="parseIconCapaianColor(kinerjaProgram.capaian, kinerjaProgram.kinerja_tidak_tercapai.length).title" @click="showKegagalan(kinerjaProgram)" class="fa-stack" style="cursor:pointer">
                                      <i class="fa fa-circle fa-stack-2x text-white"></i>
                                      <i class="fa fa-times fa-stack-1x" :class="parseIconCapaianColor(kinerjaProgram.capaian, kinerjaProgram.kinerja_tidak_tercapai.length).class"></i>
                                    </i>
                                  </div>
                                </template>
                                <b-button
                                  v-if="kinerjaProgram.kinerja_kegiatan_cross && kinerjaProgram.kinerja_kegiatan_cross.length"
                                  @click="toggleShowCrossId('kinerjaProgram', kinerjaProgram.id); keyComponent++"
                                  :variant="isShowCrossId('kinerjaProgram', kinerjaProgram.id) ? 'primary' : 'secondary'"
                                  size="sm"
                                  pill
                                  v-b-tooltip.hover
                                  title="Tampilkan cross cutting"
                                >
                                  CC
                                </b-button>
                                <b-button
                                   v-if="kinerjaProgram.do_rumus"
                                  @click="
                                    $nuxt.$emit(
                                      'show-modal-definisi-operasional',
                                      satkerId,
                                      satkerSasaran.id,
                                      satkerSasaran.iku,
                                      kinerjaProgram.id
                                    )
                                  "
                                  variant="primary"
                                  size="sm"
                                  pill
                                  v-b-tooltip.hover
                                  title="Definisi Operasional Program"
                                >
                                  DO
                                </b-button>
                                 <b-button class="mr-2"
                            @click="
                              $nuxt.$emit(
                                'show-modal-berbagi-peran',
                                kinerjaProgram.sasaran ,
                                null,
                                kinerjaProgram.id
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Berbagi Peran"
                          >
                            BP
                          </b-button>
                                <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-why',
                                  satkerId,
                                  sasaranRpjmd.id,
                                  satkerSasaran.id,
                                  satkerSasaran.iku,
                                  kinerjaProgram.id
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Why"
                            class="mr-1"
                          >
                            W
                          </b-button>
                      
                                 <b-button class="mr-1"
                                  v-if="options.showIconEditKinerja"
                                  @click="
                                    onEditKinerja(
                                      'kinerjaProgram',
                                      kinerjaProgram.id
                                    )
                                  "
                                  variant="secondary"
                                  size="sm"
                                  pill
                                  v-b-tooltip.hover
                                  title="Edit Kinerja Program"
                                >
                                  <i class="fa fa-edit" aria-hidden="true"></i>
                                </b-button>
                                <b-button
                                  v-if="options.showIconEditKinerja"
                                    @click="
                                      onAddKinerja(
                                        'kinerjaKegiatan',  kinerjaProgram.id
                                      )
                                    "
                                    variant="dark"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    title="Tambah Kinerja Kegiatan"
                                  >
                                    <i
                                      class="fa fa-plus"
                                      aria-hidden="true"
                                    ></i>
                                  </b-button>
                               
                              </div>
                              <div v-if="kinerjaProgram.is_cross" class="mb-2 d-flex align-items-baseline w-100">
                                <i class="fa fa-sitemap mr-2"></i>
                                <div>
                                  <small>Satuan Kerja:</small>
                                  <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaProgram.satuan_kerja.satuan_kerja_nama }}</h6>
                                </div>
                              </div>
                              <div class="d-flex align-items-baseline w-100">
                                <i class="fa fa-bullseye mr-2"></i>
                                <div>
                                  <small>{{ labels.lv3.sasaran }}:</small>
                                  <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaProgram.sasaran }}</h6>
                                </div>
                              </div>
                              <div v-if="visibility.indikator" class="mt-2 indikator d-flex align-items-baseline w-100">
                                <i class="fa fa-tachometer mr-2"></i>
                                <div>
                                  <small>{{ labels.lv3.indikator }}:</small>
                                  <template v-if="kinerjaProgram.indikator_merge && kinerjaProgram.indikator_merge.length">
                                        <h6 v-for="(indikator, indikatorIndex) in kinerjaProgram.indikator_merge" :key="indikatorIndex" class="m-0  font-wight-bold" style="word-wrap: anywhere;">({{ indikator }})</h6>
                                    </template>
                                    <h6 v-else class="m-0  font-wight-bold" style="word-wrap: anywhere;">({{ kinerjaProgram.indikator }})</h6>
                                </div>
                              </div>
                              
                              <hr v-if="visibility.kinerja || visibility.pengampu" class="my-2" style="border-color:white; width:100%;">
                              <div v-if="visibility.kinerja" class="d-flex align-items-baseline w-100 text-white">
                                <i class="fa fa-briefcase mr-2"></i>
                                <div>
                                  <small>Nama Program:  </small>
                                  <div class="mb-1 nama-kegiatan text-italic" style="word-wrap: anywhere;">{{ kinerjaProgram.program?.nama || '-' }}</div>
                                </div>
                              </div>

                              <div v-if="visibility.pengampu" class="mt-2 d-flex align-items-baseline w-100 text-white">
                                <i class="fa fa-user mr-2"></i>
                                <div>
                                  <small>Pengampu:  </small>
                                  <div class="pengampu" style="word-wrap: anywhere;">
                                    <div v-if="kinerjaProgram.pengampu == 'unit-kerja' && kinerjaProgram.struktur_organisasi">
                                      {{ kinerjaProgram.struktur_organisasi.jabatan_nama }}
                                    </div>
                                    <div v-else-if="kinerjaProgram.pengampu == 'tim-kerja' && kinerjaProgram.tim_kerja">
                                      {{ kinerjaProgram.tim_kerja.nama }} - {{ kinerjaProgram.tim_kerja.ketua?.peg_nama }}
                                    </div>
                                    <div v-else>-</div>
                                  </div>
                                </div>
                              </div>
                            </span>
                            <!-- level 4 - Kinerja Kegiatan -->
                            <ul v-if="maxLevel >= 4 && kinerjaProgram.kinerja_kegiatan && kinerjaProgram.kinerja_kegiatan.length">
                              <template v-for="kinerjaKegiatan of mergeKinerjaKegiatan(kinerjaProgram.id, kinerjaProgram.kinerja_kegiatan, kinerjaProgram.kinerja_kegiatan_cross)">
                                <li :key="`${kinerjaKegiatan.id}-${kinerjaKegiatan.is_cross}`"   :id="`kinerja-kegiatan-${kinerjaKegiatan.id}-${kinerjaKegiatan.is_cross}`" v-if="showBayangan ? satkerSasaran.render_status != false && kinerjaProgram.render_status != false && kinerjaKegiatan.render_status != false : true" :class="{cross: kinerjaKegiatan.is_cross, 'cross-exists': kinerjaKegiatan.kinerja_kegiatan_cross_exists}">
                                  <span :class="{highlight: options.highlight == `kinerja-kegiatan-${kinerjaKegiatan.id}-${kinerjaKegiatan.is_cross}`}" class="text-left kinerjaKegiatan lightblue-box">
                                    <div v-if="!kinerjaKegiatan.is_cross && showBayangan && kinerjaKegiatan.kinerja_bayangan" class="mb-2 kinerja-bayangan-container p-5 rounded-lg" style="padding-left:10px !important; margin-top: -7px;width: calc(100% + 20px)">
                                      <div class="d-flex align-items-baseline w-100">
                                        <i class="fa fa-bullseye mr-2"></i>
                                        <div>
                                          <small>{{ labels.lv4.sasaran }}:</small>
                                          <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaKegiatan.kinerja_bayangan.sasaran }}</h6>
                                        </div>
                                      </div>
                                      <div class="mt-2 d-flex align-items-baseline w-100">
                                        <i class="fa fa-tachometer mr-2"></i>
                                        <div>
                                          <small>{{ labels.lv4.indikator }}:</small>
                                          <h6 class="m-0  font-weight-bold" style="word-wrap: anywhere;">({{ kinerjaKegiatan.kinerja_bayangan.indikator }})</h6>
                                        </div>
                                      </div>
                                    </div>
                                    <small v-if="!kinerjaKegiatan.is_cross && $route.query.showSkp == 'true' && kinerjaKegiatan.skp && kinerjaKegiatan.skp.length" class="text-white">Ditandai sebagai SKP</small>
                                    <small v-if="!kinerjaKegiatan.is_cross && $route.query.showSkp == 'true' && (!kinerjaKegiatan.skp || !kinerjaKegiatan.skp.length)" class="text-white">Bukan SKP</small>
                                    <div v-if="!kinerjaKegiatan.is_cross && options.showIconCapaian" class="d-flex justify-content-between">
                                      <div>
                                        <i v-if="kinerjaKegiatan.solusi" @click="$refs['modal-solusi-masalah'].showModal('kinerja-kegiatan', kinerjaKegiatan.id)" class="fa-stack" v-b-tooltip.hover title="Solusi Kinerja" style="cursor:pointer">
                                          <i class="fa fa-circle fa-stack-2x text-white"></i>
                                          <i class="fa fa-check text-success fa-stack-1x"></i>
                                        </i>
                                      </div>
                                      <div>
                                        <i v-if="kinerjaKegiatan.kinerja_tercapai.length" @click="showKinerjaTercapaiModal(kinerjaKegiatan.kinerja_tercapai)" class="fa-stack" v-b-tooltip.hover title="CSF" style="cursor:pointer">
                                          <i class="fa fa-circle fa-stack-2x text-white"></i>
                                          <i class="fa fa-info text-info fa-stack-1x"></i>
                                        </i>
                                        <i v-if="parseIconCapaianColor(kinerjaKegiatan.capaian, kinerjaKegiatan.kinerja_tidak_tercapai.length).status" v-b-tooltip.hover :title="parseIconCapaianColor(kinerjaKegiatan.capaian, kinerjaKegiatan.kinerja_tidak_tercapai.length).title" @click="showKegagalan(kinerjaKegiatan)" class="fa-stack" style="cursor:pointer">
                                          <i class="fa fa-circle fa-stack-2x text-white"></i>
                                          <i class="fa fa-times fa-stack-1x" :class="parseIconCapaianColor(kinerjaKegiatan.capaian, kinerjaKegiatan.kinerja_tidak_tercapai.length).class"></i>
                                        </i>
                                      </div>
                                    </div>
                                      <div class="d-flex justify-content-between">
                                    <b-button class="mr-2"
                                      v-if="!kinerjaKegiatan.is_cross && kinerjaKegiatan.kinerja_sub_kegiatan_cross && kinerjaKegiatan.kinerja_sub_kegiatan_cross.length"
                                      @click="toggleShowCrossId('kinerjaKegiatan', kinerjaKegiatan.id); keyComponent++"
                                      :variant="isShowCrossId('kinerjaKegiatan', kinerjaKegiatan.id) ? 'primary' : 'secondary'"
                                      size="sm"
                                      pill
                                      v-b-tooltip.hover
                                      title="Tampilkan cross cutting"
                                    >
                                      CC
                                    </b-button>
                                    <b-button  class="mr-1"
                                        v-if="kinerjaKegiatan.do_rumus"
                                        @click="
                                          $nuxt.$emit(
                                            'show-modal-definisi-operasional',
                                            satkerId,
                                            satkerSasaran.id,
                                            satkerSasaran.iku,
                                            kinerjaProgram.id,
                                            kinerjaKegiatan.id
                                          )
                                        "
                                        variant="primary"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        title="Definisi Operasional Kegiatan"
                                      >
                                        DO
                                      </b-button>
                                            <b-button  class="mr-2"
                            @click="
                              $nuxt.$emit(
                                'show-modal-berbagi-peran',
                                null,
                                null,
                                kinerjaKegiatan.id,
                                 kinerjaKegiatan.sasaran
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Berbagi Peran"
                          >
                            BP
                          </b-button>
                                      <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-why',
                                   satkerId,
                                   sasaranRpjmd.id,
                                  satkerSasaran.id,
                                  satkerSasaran.iku,
                                  kinerjaProgram.id,
                                  kinerjaKegiatan.id
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Why"
                            class="mr-1"
                          >
                            W
                          </b-button>
                                      <b-button  class="mr-1"
                                      v-if="options.showIconEditKinerja"
                                      @click="
                                        onEditKinerja(
                                          'kinerjaKegiatan',
                                          kinerjaKegiatan.id
                                        )
                                      "
                                      variant="secondary"
                                      size="sm"
                                      pill
                                      v-b-tooltip.hover
                                      title="Edit Kinerja Kegiatan"
                                    >
                                      <i
                                        class="fa fa-edit"
                                        aria-hidden="true"
                                      ></i>
                                    </b-button>
                                     <b-button  class="mr-1"
                                  v-if="options.showIconEditKinerja"
                                    @click="
                                      onAddKinerja(
                                        'kinerjaSubKegiatan',  kinerjaKegiatan.id
                                      )
                                    "
                                    variant="dark"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    title="Tambah Kinerja Sub Kegiatan"
                                  >
                                    <i
                                      class="fa fa-plus"
                                      aria-hidden="true"
                                    ></i>
                                  </b-button>
                                   <b-button class="mr-1"
                                              v-if="options.showIconEditKinerja"
                                              @click="
                                                onCreateLinkHighlight(
                                                  'kinerja-kegiatan',
                                                  kinerjaKegiatan.id
                                                )
                                              "
                                              variant="success"
                                              size="sm"
                                              pill
                                              v-b-tooltip.hover
                                              title="Create Link"
                                            >
                                              <i class="fa fa-link" aria-hidden="true"></i>
                                            </b-button>
                                  </div>
                                    <div v-if="kinerjaKegiatan.is_cross" class="d-flex align-items-baseline w-100" style="color:black;">
                                      <i class="fa fa-sitemap mr-2"></i>
                                      <div>
                                        <small>Satuan Kerja:</small>
                                        <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaKegiatan.satuan_kerja.satuan_kerja_nama }}</h6>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-baseline w-100" style="color:black;">
                                      <i class="fa fa-bullseye mr-2"></i>
                                      <div>
                                        <small>{{ labels.lv4.sasaran }}:</small>
                                        <h6 class="m-0 mb-1 sasaran-title font-weight-bold" style="word-wrap: anywhere;">{{ kinerjaKegiatan.sasaran }}</h6>
                                      </div>
                                    </div>
                                    <div v-if="visibility.indikator" class="mt-2 d-flex align-items-baseline w-100" style="color:black;">
                                      <i class="fa fa-tachometer mr-2"></i>
                                      <div>
                                        <small>{{ labels.lv4.indikator }}:</small>
                                        <h6 class="m-0 mb-1 indikator font-weight-bold" style="word-wrap: anywhere;">({{ kinerjaKegiatan.indikator }})</h6>
                                      </div>
                                    </div>
                                    <hr v-if="visibility.kinerja || visibility.pengampu" class="my-2" style="border-color:white; width:100%;">
                                    <div v-if="visibility.kinerja" class="d-flex align-items-baseline w-100 text-white">
                                      <i class="fa fa-briefcase mr-2"></i>
                                      <div>
                                        <small>Nama Kegiatan:  </small>
                                        <div class="mb-1 nama-kegiatan text-italic" style="word-wrap: anywhere;">{{ kinerjaKegiatan.kegiatan?.nama || '-' }}</div>
                                      </div>
                                    </div>

                                    <div v-if="visibility.pengampu" class="mt-2 d-flex align-items-baseline w-100 text-white">
                                      <i class="fa fa-user mr-2"></i>
                                      <div>
                                        <small>Pengampu:  </small>
                                        <div class="pengampu" style="word-wrap: anywhere;">
                                          <div v-if="kinerjaKegiatan.pengampu == 'unit-kerja' && kinerjaKegiatan.struktur_organisasi">
                                            {{ kinerjaKegiatan.struktur_organisasi.jabatan_nama }}
                                          </div>
                                          <div v-else-if="kinerjaKegiatan.pengampu == 'tim-kerja' && kinerjaKegiatan.tim_kerja">
                                            {{ kinerjaKegiatan.tim_kerja.nama }} - {{ kinerjaKegiatan.tim_kerja.ketua?.peg_nama }}
                                          </div>
                                          <div v-else>-</div>
                                        </div>
                                      </div>
                                    </div>

                                  </span>
                                  <!-- level 5 - Kinerja Sub Kegiatan -->
                                  <ol v-if="maxLevel >= 5 && kinerjaKegiatan.kinerja_sub_kegiatan && kinerjaKegiatan.kinerja_sub_kegiatan.length" class="level-5-wrapper">
                                    <li v-for="kinerjaSubKegiatan of mergeKinerjaSubKegiatan(kinerjaKegiatan.id, kinerjaKegiatan.kinerja_sub_kegiatan, kinerjaKegiatan.kinerja_sub_kegiatan_cross, kinerjaKegiatan.kinerja_sub_kegiatan_kab_kota)" :key="`${kinerjaSubKegiatan.id}-${kinerjaSubKegiatan.is_cross}`" :id="`kinerja-sub-kegiatan-${kinerjaSubKegiatan.id}-${kinerjaSubKegiatan.is_cross}`" :class="{cross: kinerjaSubKegiatan.is_cross, 'cross-exists': kinerjaSubKegiatan.kinerja_sub_kegiatan_cross_exists}">
                                      <div class="text-left level-5" :class="{highlight: options.highlight == `kinerja-sub-kegiatan-${kinerjaSubKegiatan.id}-${kinerjaSubKegiatan.is_cross}`}">
                                        <small v-if="!kinerjaSubKegiatan.is_cross && $route.query.showSkp == 'true' && kinerjaSubKegiatan.skp && kinerjaSubKegiatan.skp.length" class="text-white">Ditandai sebagai SKP</small>
                                        <small v-if="!kinerjaSubKegiatan.is_cross && $route.query.showSkp == 'true' && (!kinerjaSubKegiatan.skp || !kinerjaSubKegiatan.skp.length)" class="text-white">Bukan SKP</small>
                                        <div v-if="!kinerjaSubKegiatan.is_cross" class="d-flex w-100 justify-content-between">
                                          <template v-if="options.showIconCapaian">
                                            <div>
                                              <i v-if="kinerjaSubKegiatan.solusi" @click="$refs['modal-solusi-masalah'].showModal('kinerja-sub-kegiatan', kinerjaSubKegiatan.id)" class="fa-stack" v-b-tooltip.hover title="Solusi Kinerja" style="cursor:pointer">
                                                <i class="fa fa-circle fa-stack-2x text-white"></i>
                                                <i class="fa fa-check text-success fa-stack-1x"></i>
                                              </i>
                                            </div>
                                            <div>
                                              <i v-if="kinerjaSubKegiatan.kinerja_tercapai.length" @click="showKinerjaTercapaiModal(kinerjaSubKegiatan.kinerja_tercapai)" class="fa-stack" v-b-tooltip.hover title="CSF" style="cursor:pointer">
                                                <i class="fa fa-circle fa-stack-2x text-white"></i>
                                                <i class="fa fa-info text-info fa-stack-1x"></i>
                                              </i>
                                              <i v-if="parseIconCapaianColor(kinerjaSubKegiatan.capaian, kinerjaSubKegiatan.kinerja_tidak_tercapai.length).status" v-b-tooltip.hover :title="parseIconCapaianColor(kinerjaSubKegiatan.capaian, kinerjaSubKegiatan.kinerja_tidak_tercapai.length).title" @click="showKegagalan(kinerjaSubKegiatan)" class="fa-stack" style="cursor:pointer">
                                                <i class="fa fa-circle fa-stack-2x text-white"></i>
                                                <i class="fa fa-times fa-stack-1x" :class="parseIconCapaianColor(kinerjaSubKegiatan.capaian, kinerjaSubKegiatan.kinerja_tidak_tercapai.length).class"></i>
                                              </i>
                                            </div>
                                            
                                          </template>
                                          <div>
                                            <i class="fa-stack" v-if="!kinerjaSubKegiatan.is_kab_kota" @click="showKamusIndikatorModal(kinerjaSubKegiatan.indikator, kinerjaSubKegiatan.indikator_kemendagri)" v-b-tooltip.hover title="Kamus Indikator" style="cursor:pointer">
                                              <i class="fa fa-circle fa-stack-2x text-white"></i>
                                              <i class="fa text-info fa-stack-1x"><b>K</b></i>
                                            </i>
                                            <b-button class="mr-1"
                                              v-if="options.showIconEditKinerja && !kinerjaSubKegiatan.is_kab_kota"
                                              @click="
                                                onEditKinerja(
                                                  'kinerjaSubKegiatan',
                                                  kinerjaSubKegiatan.id
                                                )
                                              "
                                              variant="secondary"
                                              size="sm"
                                              pill
                                              v-b-tooltip.hover
                                              title="Edit Kinerja Sub Kegiatan"
                                            >
                                              <i class="fa fa-edit" aria-hidden="true"></i>
                                            </b-button>
                                            <b-button 
                            @click="
                              $nuxt.$emit(
                                'show-modal-why',
                                   satkerId,
                                   sasaranRpjmd.id,
                                    satkerSasaran.id,
                                    satkerSasaran.iku,
                                    kinerjaProgram.id,
                                    kinerjaKegiatan.id,
                                    kinerjaSubKegiatan.id
                              )
                            "
                            variant="primary"
                            size="sm"
                            pill
                            v-b-tooltip.hover
                            title="Why"
                            class="mr-1"
                          >
                            W
                          </b-button>
                                            <b-button class="mr-1"
                                              v-if="options.showIconEditKinerja && !kinerjaSubKegiatan.is_kab_kota"
                                              @click="
                                                onCreateLinkHighlight(
                                                  'kinerja-sub-kegiatan',
                                                  kinerjaSubKegiatan.id
                                                )
                                              "
                                              variant="success"
                                              size="sm"
                                              pill
                                              v-b-tooltip.hover
                                              title="Create Link"
                                            >
                                              <i class="fa fa-link" aria-hidden="true"></i>
                                            </b-button>
                                            
                                           
                                          </div>
                                          <i v-if="kinerjaSubKegiatan.is_kab_kota" v-b-tooltip.hover title="Kinerja Eksternal Kabupaten Kota" >
                                             <b-button class="mr-1"
                                              title="Edit Kinerja Sub Kegiatan"
                                               size="sm"
                                              pill
                                              variant="primary"
                                            >
                                            Ex Kab Kota
                                            </b-button>
                                          </i>
                                          <i v-if="kinerjaSubKegiatan.is_external" v-b-tooltip.hover title="Kinerja Eksternal" class="fa-stack">
                                            <i class="fa fa-circle fa-stack-2x text-info"></i>
                                            <i class="fa text-white fa-stack-1x"><b>Ex</b></i>
                                          </i>
                                        </div>
                                        <div v-if="kinerjaSubKegiatan.is_cross" class="d-flex align-items-baseline w-100" style="color:black;">
                                          <i class="fa fa-sitemap mr-2"></i>
                                          <div>
                                            <small>Satuan Kerja:</small>
                                            <h6 class="m-0 mb-1 sasaran-title font-weight-bold">{{ kinerjaSubKegiatan.satuan_kerja.satuan_kerja_nama }}</h6>
                                          </div>
                                        </div>
                                        <div class="d-flex align-items-baseline w-100" style="color:black;">
                                          <i class="fa fa-bullseye mr-2"></i>
                                          <div>
                                            <small>{{ labels.lv5.sasaran }}:</small>
                                            <h6 class="m-0 mb-1 sasaran-title font-weight-bold">{{ kinerjaSubKegiatan.sasaran }}</h6>
                                          </div>
                                        </div>
                                        <div v-if="visibility.indikator" class="mt-2 d-flex align-items-baseline w-100" style="color:black;">
                                          <i class="fa fa-tachometer mr-2"></i>
                                          <div>
                                            <small>{{ labels.lv5.indikator }}:</small>
                                            <h6 class="m-0 mb-1 indikator font-weight-bold" style="word-wrap: anywhere;">({{ kinerjaSubKegiatan.indikator }})</h6>
                                            <small class="d-block mt-2">{{ kinerjaSubKegiatan.target }} {{ kinerjaSubKegiatan.satuan }}</small>
                                          </div>
                                        </div>
                                        <hr v-if="visibility.kinerja || visibility.pengampu" class="my-2" style="border-color:white; width:100%;">
                                        <div v-if="visibility.pengampu" class="mb-2 d-flex align-items-baseline w-100 text-white" >
                                          <i class="fa fa-user mr-2"></i>
                                          <div>
                                            <small :class="{'text-primary': kinerjaSubKegiatan.is_kab_kota}">Pengampu:  </small>
                                            <div class="pengampu" style="word-wrap: anywhere;">
                                              <div v-if="kinerjaSubKegiatan.pengampu == 'unit-kerja' && kinerjaSubKegiatan.struktur_organisasi">
                                                {{ kinerjaSubKegiatan.struktur_organisasi.jabatan_nama }} - {{ kinerjaSubKegiatan.struktur_organisasi.unit_kerja_nama_full }}
                                              </div>
                                              <div v-else-if="kinerjaSubKegiatan.pengampu == 'tim-kerja' && kinerjaSubKegiatan.tim_kerja">
                                                {{ kinerjaSubKegiatan.tim_kerja.nama }} - {{ kinerjaSubKegiatan.tim_kerja.ketua?.peg_nama }}
                                              </div>
                                              <div v-else-if="kinerjaSubKegiatan.is_kab_kota" class="text-primary">
                                                Pemerintah Daerah {{ kinerjaSubKegiatan.kinerja_kab_kota.kabupaten_kota }}
                                              </div>
                                              <div v-else>-</div>
                                            </div>
                                          </div>
                                        </div>

                                        <div v-if="visibility.kinerja" class="d-flex align-items-baseline w-100 text-white" >
                                          <i class="fa fa-briefcase mr-2"></i>
                                          <div>
                                            <small>Nama Sub Kegiatan:  </small>
                                            <div class="mb-1 nama-kegiatan text-italic" style="word-wrap: anywhere;">{{ kinerjaSubKegiatan.sub_kegiatan?.nama || '-' }}</div>
                                          </div>
                                        </div>
                                      </div>
                                    </li>
                                  </ol>
                                </li>
                              </template>
                            </ul>
                          </li>
                        </template>
                      </ul>
                    </li>
                  </template>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </HandyScroll>
    </div>

    <b-modal id="kegagalan-modal" size="lg" title="Penyebab Kegagalan" hide-footer>
      <div v-if="kegagalanModalData">
        <div>
          <b>Target</b> <br>
          {{ kegagalanModalData.target }}
        </div>
        <div class="mt-2">
          <b>Realisasi</b> <br>
          {{ kegagalanModalData.realisasi }}
        </div>
        <div class="mt-2">
          <b>Capaian</b> <br>
          {{ kegagalanModalData.capaian }}
        </div>
        <div class="mt-2">
          <b>Penyebab Kegagalan</b> <br>
          <span style="white-space: break-spaces;">{{ kegagalanModalData.penyebab_kegagalan }}</span>
        </div>
        <div class="mt-2">
          <b>Diagnosa CSF Gagal</b> <br>
          <template v-if="kegagalanModalData.catatan.length">
            <ul>
              <li v-for="(catatan, index) in kegagalanModalData.catatan" :key="index" style="white-space: break-spaces;">{{ catatan }}</li>
            </ul>
          </template>
          <span v-else>-</span>
        </div>
      </div>
    </b-modal>

    <b-modal id="kinerja-tercapai-modal" size="md" title="Diagnosa Critical Success Factor (CSF)" hide-footer>
      <ul>
        <li v-for="kinerja in kinerjaTercapaiData" :key="kinerja.id" class="mb-1">
          <span style="white-space: break-spaces;">{{ kinerja.catatan }}</span>
        </li>
      </ul>
    </b-modal>

    <b-modal id="kamus-indikator-modal" size="lg" title="Kamus Indikator" hide-footer>
      <b-table-simple responsive bordered>
        <b-thead class="text-center align-middle" head-variant="info">
          <b-tr>
            <b-th>Indikator Output</b-th>
            <b-th>Indikator Sub Kegiatan Versi Kemendagri</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr>
            <b-td>{{ kamusIndikatorData.indikator || '-' }}</b-td>
            <b-td>{{ kamusIndikatorData.indikatorKemendagri || '-' }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-modal>

    <ModalSolusiMasalah ref="modal-solusi-masalah" />
    <ModalRencanaAksi @go-to-indikator="goToKinerjaSubKegiatan($event)" />
    <ModalRencanaAksiGubernur @go-to-indikator="goToKinerjaSubKegiatan($event)" />
    <ModalRealisasiRencanaAksi @go-to-indikator="goToKinerjaSubKegiatan($event)" />
    <ModalRealisasiRencanaAksiGubernur @go-to-indikator="goToKinerjaSubKegiatan($event)" />
    <ModalDefinisiOperasional />
    <ModalWhy />
    <ModalBerbagiPeran />
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import { api as fullscreen } from 'vue-fullscreen'
import { toPng } from 'html-to-image';
import download from 'downloadjs'
import HandyScroll from 'vue-handy-scroll';
import ModalSolusiMasalah from '~/components/modals/SolusiMasalah.vue'
import ModalRencanaAksi from '~/components/modals/RencanaAksi.vue'
import ModalRencanaAksiGubernur from '~/components/modals/RencanaAksiGubernur.vue'
import ModalRealisasiRencanaAksi from '~/components/modals/RealisasiRencanaAksi.vue'
import ModalRealisasiRencanaAksiGubernur from '~/components/modals/RealisasiRencanaAksiGubernur.vue'
import ModalDefinisiOperasional from "~/components/modals/DefinisiOperasional.vue";
import ModalWhy from "~/components/modals/Why.vue"
import ModalBerbagiPeran from "~/components/modals/BerbagiPeran.vue"
export default {
  props: {
    data: {
      required: true,
    },
    satkerList: {
      default() {
        return []
      },
    },
    satkerId: {
      default: null,
    },
    maxLevel: {
      required: false,
      default: 5,
    },
    type: {
      default: 'default'
    },
    showSwitchIconCapaian: {
      type: Boolean,
      default: false
    },
    showSwitchIconEditKinerja: {
      type: Boolean,
      default: false
    },
    showBayangan: {
      type: Boolean,
      default: false,
    },
    tahunKinerja: {
      type: Number,
      default: function () {
        return this.$helper.getTahunKinerja()
      }
    },
  },

  components: {
    HandyScroll,
    ModalSolusiMasalah,
    ModalRencanaAksi,
    ModalRencanaAksiGubernur,
    ModalRealisasiRencanaAksi,
    ModalRealisasiRencanaAksiGubernur,
    ModalDefinisiOperasional,
    ModalWhy,
    ModalBerbagiPeran
  },
  
  data() {
    return {
      keyComponent: 1,
      fullscreen: false,
      teleport: true,
      showDiagram: true,
      renderedImage: null,
      visibility: {
        sasaran: true,
        indikator: true,
        pengampu: true,
        kinerja: true,
      },
      labels: {
        lv1: {
          sasaran: 'Sasaran Strategis RPJMD ( Ultimate Outcome)',
          indikator: 'Indikator Sasaran Strategis RPJMD ( Ultimate Outcome)',
        },
        lv2: {
          sasaran: 'Sasaran Strategis Renstra PD ( Intermediate Outcome Bidang Urusan)',
          indikator: 'Indikator Sasaran Strategis Renstra PD ( Intermediate Outcome Bidang Urusan)',
        },
        lv3: {
          sasaran: 'Sasaran Program (Intermediate Outcome)',
          indikator: 'Indikator Sasaran Program (Intermediate Outcome)',
        },
        lv4: {
          sasaran: 'Sasaran Kegiatan (Immediate Outcome)',
          indikator: 'Indikator Kegiatan (Immediate Outcome)',
        },
        lv5: {
          sasaran: 'Sasaran Sub Kegiatan (Output)',
          indikator: 'Indikator Sub Kegiatan (Output)',
        },
      },
      options: {
        export: {
          pixelRatio: 1
        },
        showIconCapaian: false,
        showCross: false,
        showCrossIds: [],
        highlight: '',
        showIconEditKinerja: false
      },
      kegagalanModalData: null,
      kinerjaTercapaiData: [],
      isBusy: {
        render: false,
        export: false,
        exportExcel: false,
      },
      kamusIndikatorData: {
        indikator: null,
        indikatorKemendagri: null,
      },
      autoShowModal: {
        type: this.$route.query.autoShowModalType || null,
        id: this.$route.query.autoShowModalId || null,
        highlight: ''
      },
      autoScrollToKinerjaSubKegiatan:{
         type: this.$route.query.autoScrollToKinerjaSubKegiatanType || null,
        id: this.$route.query.autoScrollToKinerjaSubKegiatanId || null,
        highlight: ''
      }
    }
  },
  computed: {
    satker() {
      if (!this.satkerId) return null;

      return this.satkerList.find(el => el.satuan_kerja_id == this.satkerId)
    }
  },
  watch: {
    'options.showIconCapaian': function () {
      this.keyComponent++
    },
    'options.showCross': function (value) {
      if (value) {
        const temp = []

        this.data.forEach(sasaranRpjmd => {
          temp.push(`sasaranRpjmd-${sasaranRpjmd.id}`)

          sasaranRpjmd.sasaran_strategis_pd.forEach(satkerSasaran => {
            temp.push(`satkerSasaran-${satkerSasaran.id}`)

            satkerSasaran.kinerja_program.forEach(kinerjaProgram => {
              temp.push(`kinerjaProgram-${kinerjaProgram.id}`)

              kinerjaProgram.kinerja_kegiatan.forEach(kinerjaKegiatan => {
                temp.push(`kinerjaKegiatan-${kinerjaKegiatan.id}`)
              });
            });
          });
        });

        this.options.showCrossIds = temp
      } else {
        this.options.showCrossIds = []
      }

      this.keyComponent++
    },
    'keyComponent': function () {
      this.setWidthHeight()
    },
  },
  created() {
    switch (this.type) {
      case 'arsitektur-kinerja':
        this.visibility.pengampu = false
        this.visibility.kinerja = false

        this.labels.lv1.sasaran = 'Ultimate Outcome'
        this.labels.lv2.sasaran = 'Intermediate Outcome'
        this.labels.lv3.sasaran = 'Intermediate Outcome'
        this.labels.lv4.sasaran = 'Immediate outcome'
        this.labels.lv5.sasaran = 'Output'

        this.labels.lv1.indikator = 'Indikator Ultimate Outcome'
        this.labels.lv2.indikator = 'Indikator Intermediate Outcome'
        this.labels.lv3.indikator = 'Indikator Intermediate Outcome'
        this.labels.lv4.indikator = 'Indikator Immediate outcome'
        this.labels.lv5.indikator = 'Indikator Output'
        break;
    
      default:
        break;
    }

    if(this.showSwitchIconEditKinerja == true){
      this.options.showIconEditKinerja = true
    }
  },
  mounted() {
    this.setWidthHeight()

    setTimeout(() => {
      this.showModalAuto()
      this.showModalAutoKinerjaSubKegiatan()
    }, 500);


  },
  methods: {
    toggle () {
      fullscreen.toggle(this.$el.querySelector('.fullscreen-wrapper'), {
        teleport: this.teleport,
        callback: (isFullscreen) => {
          this.fullscreen = isFullscreen
        },
      })
    },
    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/display-mikro/cascading/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Cascading.xlsx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.exportExcel = false
      }
    },
    exportDiagramImage(name) {
      this.isBusy.export = true
      const node = document.getElementById('diagram');

      toPng(node, { pixelRatio: this.options.export.pixelRatio})
        .then(function (dataUrl) {
          const img = new Image();
          img.src = dataUrl;

          download(dataUrl, 'Diagram Sasaran ' + name +' ' + Date.now() + '.png'||'diagram-sasaran.png');
        })
        .catch(function (error) {
          console.error('oops, something went wrong!', error);
          alert('Mohon maaf, silahkan ulangi lagi proses export')
        })
        .finally(() => this.isBusy.export = false)
    },
    async renderImage() {
      this.isBusy.render = true
      var node = document.getElementById('diagram');

      toPng(node, { pixelRatio: 1 })
        .then(image => {
          this.renderedImage = image
          this.showDiagram = false;
        })
        .catch(function (error) {
          console.error('oops, something went wrong!', error);
          alert('Mohon maaf, silahkan ulangi lagi proses export')
        })
        .finally(() => this.isBusy.render = false)
    },
    setSameHeight(className) {
      let maxHeight = 0;

      $(`.${className}`).each(function() {
        const height = $(this).outerHeight()
        maxHeight = height > maxHeight ? height : maxHeight
      });

      $(`.${className}`).css('height', maxHeight);
    },
    setSameWidth(className) {
      let maxWidth = 0;

      $(`.${className}`).each(function() {
        const width = $(this).outerWidth()
        maxWidth = width > maxWidth ? width : maxWidth
      });

      $(`.${className}`).css('width', maxWidth);
    },
    showKegagalan(data) {
      this.kegagalanModalData = {
        target: data.target || '-',
        realisasi: data.realisasi || '-',
        capaian: data.capaian || '-',
        penyebab_kegagalan: data.penyebab_kegagalan || '-',
        catatan: data.kinerja_tidak_tercapai.map(_ => _.catatan),
      }

      this.$bvModal.show('kegagalan-modal')
    },
    setWidthHeight() {
      this.$nextTick(() => {
        // set width dulu baru height supaya render merata
        setTimeout(() => {
          this.setSameWidth('sasaranRpjmd')
          this.setSameWidth('satkerSasaran')
          this.setSameWidth('kinerjaProgram')
          this.setSameWidth('kinerjaKegiatan')
        }, 1000);
  
        setTimeout(() => {
          // type arsitektur-kinerja tidak set height karena ada yang telalu tinggi
          if (this.type == 'default') {
            this.setSameHeight('sasaranRpjmd')
            this.setSameHeight('satkerSasaran')
            this.setSameHeight('kinerjaProgram')
            this.setSameHeight('kinerjaKegiatan')
          }
        }, 1500);
      });
    },
    showKinerjaTercapaiModal(data) {
      this.kinerjaTercapaiData = data

      this.$bvModal.show('kinerja-tercapai-modal')
    },
    /**
     * kuning = hanya capaian otomatis
     * merah = hanya kinerja tidak tercapai (CSF gagal)
     * biru = kuning + merah
     */
    parseIconCapaianColor(capaian, kinerjaTidakTercapai) {
      const gagal = !capaian || capaian < 100

      if (gagal && !kinerjaTidakTercapai) {
        return {
          status: true,
          class: 'text-warning',
          title: 'Tidak tercapai'
        }
      } else if (!gagal && kinerjaTidakTercapai) {
        return {
          status: true,
          class: 'text-danger',
          title: 'CSF Gagal'
        }
      } else if (gagal && kinerjaTidakTercapai) {
        return {
          status: true,
          class: 'text-info',
          title: 'Tidak tercapai & CSF Gagal'
        }
      } else {
        // tidak ada kegagalan apapun
        return {
          status: false,
          class: null,
          title: null
        }
      }
    },
    showKamusIndikatorModal(indikator, indikatorKemendagri) {
      this.kamusIndikatorData.indikator = indikator
      this.kamusIndikatorData.indikatorKemendagri = indikatorKemendagri

      this.$bvModal.show('kamus-indikator-modal')
    },
    mergeKinerjaProgram(satkerSasaranId, kinerjaProgram, kinerjaProgramCross) {
      kinerjaProgram = kinerjaProgram.map(kinerja => ({
        ...kinerja,
        is_cross: false,
      }))

      if (!this.isShowCrossId('satkerSasaran', satkerSasaranId)) {
        return kinerjaProgram
      }

      kinerjaProgramCross = (kinerjaProgramCross || []).map(cross => ({
        ...cross.kinerja_program,
        is_cross: true,
      }))

      return kinerjaProgram.concat(kinerjaProgramCross)
    },
    mergeKinerjaKegiatan(kinerjaProgramId, kinerjaKegiatan, kinerjaKegiatanCross) {
      kinerjaKegiatan = kinerjaKegiatan.map(kinerja => ({
        ...kinerja,
        is_cross: false,
      }))

      if (!this.isShowCrossId('kinerjaProgram', kinerjaProgramId)) {
        return kinerjaKegiatan
      }

      kinerjaKegiatanCross = (kinerjaKegiatanCross || []).map(cross => ({
        ...cross.kinerja_kegiatan,
        is_cross: true,
      }))

      return kinerjaKegiatan.concat(kinerjaKegiatanCross)
    },
    mergeKinerjaSubKegiatan(kinerjaKegiatanId, kinerjaSubKegiatan, kinerjaSubKegiatanCross, kinerjaSubKegiatanKabKota) {
      kinerjaSubKegiatan = kinerjaSubKegiatan.map(kinerja => ({
        ...kinerja,
        is_cross: false,
        is_kab_kota:false
      }))

      kinerjaSubKegiatanKabKota = kinerjaSubKegiatanKabKota.map(kinerja => ({
        ...kinerja,
        is_cross: false,
        is_kab_kota:true
      }))

      kinerjaSubKegiatan=  kinerjaSubKegiatan.concat(kinerjaSubKegiatanKabKota)

      if (!this.isShowCrossId('kinerjaKegiatan', kinerjaKegiatanId)) {
        return kinerjaSubKegiatan
      }

      kinerjaSubKegiatanCross = (kinerjaSubKegiatanCross || []).map(cross => ({
        ...cross.kinerja_sub_kegiatan,
        is_cross: true,
         is_kab_kota:false
      }))
      
      return kinerjaSubKegiatan.concat(kinerjaSubKegiatanCross)
    },
    mergeSasaranStrategisPd(sasaranRpjmdId, sasaranStrategisPd, sasaranStrategisPdCross) {
      sasaranStrategisPd = sasaranStrategisPd.map(item => ({
        ...item,
        is_cross: false,
      }))

      if (!this.isShowCrossId('sasaranRpjmd', sasaranRpjmdId)) {
        return sasaranStrategisPd
      }

      sasaranStrategisPdCross = (sasaranStrategisPdCross || []).map(cross => ({
        ...cross.sasaran_strategis_pd,
        is_cross: true,
      }))

      return sasaranStrategisPd.concat(sasaranStrategisPdCross)
    },
    toggleShowCrossId(type, id) {
      id = `${type}-${id}`

      if (this.options.showCrossIds.includes(id)) {
        this.options.showCrossIds = this.options.showCrossIds.filter(_id => _id != id)
      } else {
        this.options.showCrossIds.push(id)
      }
    },
    isShowCrossId(type, id) {
      return this.options.showCrossIds.includes(`${type}-${id}`)
    },
    goToKinerjaKegiatan(id){
      id = `kinerja-kegiatan-${id}-false`
      const el = document.getElementById(id)
      console.log("ATB");
      console.log(el)
      if (el) {
        this.options.highlight = id

        this.$nextTick(() => {
          setTimeout(() => {
            el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

            setTimeout(() => {
              this.options.highlight = ''
            }, 1000 * 15.8);
          }, 500);
        })
      }
    },
    goToKinerjaSubKegiatan(id) {
      id = `kinerja-sub-kegiatan-${id}-false`
      const el = document.getElementById(id)
      if (el) {
        this.options.highlight = id

        this.$nextTick(() => {
          setTimeout(() => {
            el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

            setTimeout(() => {
              this.options.highlight = ''
            }, 1000 * 15.8);
          }, 500);
        })
      }
    },
    onCreateLinkHighlight(jenis, id){
      console.log(this.$helper.getTahunKinerja());
      console.log(this.satker.satuan_kerja_id );
      var link = `https://japati.jabarprov.go.id/public-display/display-mikro/cascading?tahun_kinerja=${this.$helper.getTahunKinerja()}&satuan_kerja_id=${this.satker.satuan_kerja_id }&autoScrollToKinerjaSubKegiatanType=${jenis}&autoScrollToKinerjaSubKegiatanId=${id}`
      console.log(link)
       try {
        navigator.clipboard.writeText(link);
        Swal.fire('Berhasil', 'Berhasil Salin Link', 'success');
    } catch (err) {
        console.error('Failed to copy:', err);
    }
    },
     onEditKinerja(jenis, id){
      if(jenis == "kinerjaSubKegiatan"){
        this.$router.push({ path: `/kinerja-sub-kegiatan/${id}/edit`, query: { from: 'cascading' } });
      }
      if(jenis == "kinerjaKegiatan"){
        this.$router.push({ path: `/kinerja-kegiatan/${id}/edit`, query: { from: 'cascading' } });
      }
      if(jenis == "kinerjaProgram"){
        this.$router.push({ path: `/kinerja-program/${id}/edit`, query: { from: 'cascading' } });
      }
      if(jenis == "sasaranStrategisPd"){
        this.$router.push({ path: `/sasaran-strategis-pd/${id}/edit`, query: { from: 'cascading' } });
      }
    },
    onAddKinerja(jenis, id){
      if(jenis == "kinerjaSubKegiatan"){
        this.$router.push({ path: '/kinerja-sub-kegiatan/create', query: { from: 'cascading', id: id } });
      }
      if(jenis == "kinerjaKegiatan"){
        this.$router.push({ path: '/kinerja-kegiatan/create', query: { from: 'cascading', id: id } });
      }
      if(jenis == "kinerjaProgram"){
        this.$router.push({ path: '/kinerja-program/create', query: { from: 'cascading', id: id  } });
      }
       
    },
    onAddKinerjaSasaranStrategisPd(jenis, sasaran_strategis_id, satker_rpjmd_id, indikator_id){
      if(jenis == "sasaranStrategisPd") {
        this.$router.push({ path: '/sasaran-strategis-pd/create', query: { from: 'cascading', id: sasaran_strategis_id, satker_rpjmd_id: satker_rpjmd_id, indikator_id: indikator_id } });
      }
    },
     async showModalAutoKinerjaSubKegiatan() {
      if (!this.autoScrollToKinerjaSubKegiatan.type) {
        return
      }
      console.log(this.autoScrollToKinerjaSubKegiatan.type);
      console.log(this.autoScrollToKinerjaSubKegiatan.id);
      switch (this.autoScrollToKinerjaSubKegiatan.type) {
        case 'kinerja-sub-kegiatan':
          this.goToKinerjaSubKegiatan(this.autoScrollToKinerjaSubKegiatan.id);
          
          break;
        case 'kinerja-kegiatan':
          this.goToKinerjaKegiatan(this.autoScrollToKinerjaSubKegiatan.id);
          
          break;
        default:
          break;
      }

      // remove autoShowModal from query to prevent it from showing again
      const {autoShowModalType, autoShowModalId,autoScrollToKinerjaSubKegiatanType, autoScrollToKinerjaSubKegiatanId, ...query} = this.$route.query
  
      this.$router.push({
        query,
      })
    },
    async showModalAuto() {
      if (!this.autoShowModal.type) {
        return
      }

      let id, el, firstSasaranPd

      switch (this.autoShowModal.type) {
        /**
         * TODO: saat ini this.autoShowModal.id masih bisa kosong
         * jadi ambil id pertama
         */
        case 'rencana-aksi':
          firstSasaranPd = (this.data || []).find(sasaran => Array.isArray(sasaran.sasaran_strategis_pd) && sasaran.sasaran_strategis_pd.length > 0);
          id = firstSasaranPd ? firstSasaranPd.sasaran_strategis_pd[0].id : null;
          el = document.getElementById(`rencana-aksi-${id}-btn`)
          
          if (el) {
            this.autoShowModal.highlight = `rencana-aksi-${id}`

            this.$nextTick(() => {
              setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

                setTimeout(() => {
                  this.autoShowModal.highlight = ''
                }, 1000 * 15.8);

                el.click()
              }, 500);
            })
          }
          
          break;

        /**
         * TODO: saat ini this.autoShowModal.id masih bisa kosong
         * jadi ambil id pertama
         */
        case 'realisasi-rencana-aksi':
          firstSasaranPd = (this.data || []).find(sasaran => Array.isArray(sasaran.sasaran_strategis_pd) && sasaran.sasaran_strategis_pd.length > 0);
          id = firstSasaranPd ? firstSasaranPd.sasaran_strategis_pd[0].id : null;
          el = document.getElementById(`realisasi-rencana-aksi-${id}-btn`)
          
          if (el) {
            this.autoShowModal.highlight = `realisasi-rencana-aksi-${id}`

            this.$nextTick(() => {
              setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

                setTimeout(() => {
                  this.autoShowModal.highlight = ''
                }, 1000 * 15.8);

                el.click()
              }, 500);
            })
          }
          break;

        case 'kinerja-kegiatan-cross-cutting':
          id = this.autoShowModal.id

          if (!id) {
            break;
          }

          // set tampilkan cross cutting
          this.options.showCross = true

          // wait rendering
          await new Promise(resolve => setTimeout(resolve, 1500))

          const crossCuttingElId = `kinerja-sub-kegiatan-${id}-true`
          el = document.getElementById(crossCuttingElId)

          if (el) {
            this.options.highlight = crossCuttingElId

            this.$nextTick(() => {
              setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

                setTimeout(() => {
                  this.options.highlight = ''
                }, 1000 * 15.8);

                el.click()
              }, 500);
            })
          }
          break;

        case 'kinerja-kegiatan-eksternal':
          id = this.autoShowModal.id

          if (!id) {
            break;
          }

          let elId = `kinerja-sub-kegiatan-${id}-false`
          el = document.getElementById(elId)

          if (!el) {
            elId = `kinerja-sub-kegiatan-${id}-true`
            el = document.getElementById(elId)
          }

          if (el) {
            this.options.highlight = elId

            this.$nextTick(() => {
              setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center'})

                setTimeout(() => {
                  this.options.highlight = ''
                }, 1000 * 15.8);

                el.click()
              }, 500);
            })
          }
          break;
      
        default:
          break;
      }

      // remove autoShowModal from query to prevent it from showing again
      const {autoShowModalType, autoShowModalId,autoScrollToKinerjaSubKegiatanType, autoScrollToKinerjaSubKegiatanId, ...query} = this.$route.query
  
      this.$router.push({
        query,
      })
    }
  },
}
</script>
<style scoped>
  .text-blue{
    color: #2f72ac;
  }
  .pos-r{
    position: relative;
  }
  .main-box{
    padding:15px;
    border-radius: 15px;
    font-size: 15px;
    display: inline-block;
  }
  .green-box{
    background-color: #0a9348;
    color:white;
  }
  .blue-box{
    background-color: #0e5da0;
    color:white;
  }
  .yellow-box{
    background-color: #fbcc28;
    color:black;
  }
  .kinerja-bayangan-container{
    background-color: #ff7979;
    color:black;
  }
  .w-100{
    width: 100%;
  }
  .tree, .tree ul, .tree li {
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
  }

  .tree {
    margin: 0 0 1em;
    text-align: center;
  }
  /* .tree, .tree ul {
    display: table;
  } */
  .tree ul {
  width: 100%;
  }
  .tree li {
    display: table-cell;
    padding: 20px 0 0;
    vertical-align: top;
  }
  /* _________ */
  .tree li:before {
    outline: solid 1px #aaa;
    content: "";
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
  }
  .tree li:first-child:before {left: 50%;}
  .tree li:last-child:before {right: 50%;}

  .tree > li > code, .tree > li > span {
    border: none !important;
    border-radius: .5em;
    display: inline-block;
    margin: 0 0.5em 20px;
    padding: .5em 20px;
    position: relative;
    max-width: 350px;
  }
  .tree code, .tree span:not(.d-none) {
    /* border: solid .1em #aaa; */
    border-radius: .5em;
    display: inline-block;
    margin: 0 .5em 20px;
    padding: .5em 10px;
    position: relative;
    max-width: 350px;
    min-height: 120px;
    display: inline-flex !important;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  /* | */
  .tree ul:before,
  .tree code:before,
  .tree span:before {
    outline: solid 1px #aaa;
    content: "";
    height: 20px;
    left: 50%;
    position: absolute;
  }
  .tree .cross span:before {
    outline: black dashed medium;
    content: "";
    height: 20px;
    left: 50%;
    position: absolute;
  }
  .tree ul:before {
    top: -20px;
  }
  .tree code:before,
  .tree span:before {
    top: -21px;
  }

  /* The root node doesn't connect upwards */
  .tree > li {margin-top: 0; padding-top: 0;}
    .tree > li:before,
    .tree > li:after,
    .tree > li > code:before,
    .tree > li > span:before {
    outline: none;
    }
  .lightblue-box{
    background-color: #21a2dc;
    color:white;
    min-width: 200px;
  }
  .col{
    flex-basis: 0;
    flex-grow: 1;
    max-width: 100%;
  }
  .text-italic{
    font-style: italic;
  }
  .green-box .indikator{
    color: #cdff00;
  }
  .yellow-box .indikator{
    color: #0021c1;
    text-shadow: 0 0 15px white;
  }
  .blue-box .indikator{
    color: #d6e228;
  }
  /* .lightblue-box .indikator{
    color: #000000;
    text-shadow: 0 0 15px white;
  } */
  .sasaran-title{
    font-size: 15px;
  }
  .indikator{
    font-size: 15px;
  }
  .maximize-btn{
    position: absolute;
    right:0;
    top:0;
    z-index:4;
  }
  .minimize-btn{
    position: fixed;
    right:0;
    top:0;
    margin: 15px;
  }
  .nama-kegiatan {
    /* color: #000; */
    font-size: 11px;
  }
  .pengampu {
    /* color: #000; */
    font-size: 11px;
  }


  /*CUSTOM VARIABLES HERE*/

  .level-5-wrapper {
    position: relative;
    width: 80%;
    margin-left: auto;
    padding:0;
    padding-right: 8px;
    margin-right: -4px;
  }

  .level-5-wrapper::before {
    content: "";
    position: absolute;
    top: -20px;
    left: -20px;
    width: 2px;
    height: calc(100% + 20px);
    background: #aaa;
  }

  .tree .level-5-wrapper li {
      display: block;
      padding: 0;
  }
  .tree .level-5-wrapper li::before {
      outline: none;
  }
  .level-5-wrapper li + li {
    margin-top: 20px;
  }

  .level-5 {
    font-weight: normal;
    background: rgb(162, 162, 162);
    padding:5px;
    border-radius: 5px;
  }

  .level-5::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0%;
    transform: translate(-100%, -50%);
    width: 20px;
    height: 2px;
    background: #aaa;
  }
  .cross .level-5::before {
    border-bottom: thick dashed black;
    background: white;
  }
  .level-5 .sasaran-title{
    font-size:12px;
    /* color:black; */
    word-wrap: anywhere;
  }
  .level-5 .indikator{
    font-size:11px;
    /* color:rgb(0, 38, 255); */
    /* text-shadow: none; */
  }

  .tree span.kinerjaKegiatan, .tree span.kinerjaProgram,  .tree span.satkerSasaran {
    justify-content: unset;
  }

  /* border untuk kotak yang menjadi cross cutting di parentnya */
  .cross > .kinerjaProgram,
  .cross > .kinerjaKegiatan,
  .cross > .level-5 {
    border: 10px dashed yellow;
  }
  .cross > .satkerSasaran {
    border: 10px dashed black;
  }

  /**
    * border untuk kotak yang tidak menjadi cross cutting di parentnya
    * tapi menjadi cross cutting di parent lain dalam satu opd
   */
  .cross-exists > .satkerSasaran,
  .cross-exists > .kinerjaProgram,
  .cross-exists > .kinerjaKegiatan,
  .cross-exists > .level-5 {
    border: thick solid black;
  }

  .highlight {
    animation: blink 4s infinite;
  }

  @keyframes blink {
  0% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 80px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.05);
    z-index: 9999 !important;
  }
  25% {
    border: 8px solid  #ff0000 !important;
    box-shadow: 0 0 100px#ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color:  #ff0000 !important;
    transform: scale(1.1);
  }
  50% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 120px#ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color:  #ff0000 !important;
    transform: scale(1.05);
  }
  75% {
    border: 8px solid  #ff0000 !important;
    box-shadow: 0 0 100px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.1);
  }
  100% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 80px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color:  #ff0000 !important;
    transform: scale(1.05);
    z-index: 9999 !important;
  }
}


</style>
