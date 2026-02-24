<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
	middleware: ['auth'],

	async asyncData({ $helper, error }) {
		if ($helper.getTahunKinerja() != 2023) {
			error({
				message: 'Tidak dapat mengubah data selain tahun 2023',
				statusCode: 400,
			})
		}

		const { data } = await axios.get(`/rapor-kinerja/penambahan-jumlah-output`)
  
		return {
			data,
			isBusy: {
				submit: false,
			}
		}
	},

	methods: {
		async submit() {
			this.isBusy.submit = true

			await axios.post(`/rapor-kinerja/penambahan-jumlah-output`, this.data)
		
			this.isBusy.submit = false
			Swal.fire('Berhasil!', 'Berhasil simpan data', 'success')
		}
	}
}
</script>

<template>
	<b-card>
		<b-form @submit.prevent="submit">
			<b-form-group
				label="Triwulan 1"
				label-for="tw1"
				label-class="font-weight-bold"
			>
				<b-form-input
					id="tw1"
					v-model="data.tw1"
					type="number"
					min="0"
					required
				></b-form-input>
			</b-form-group>
			<b-form-group
				label="Triwulan 2"
				label-for="tw2"
				label-class="font-weight-bold"
			>
				<b-form-input
					id="tw2"
					v-model="data.tw2"
					type="number"
					min="0"
					required
				></b-form-input>
			</b-form-group>
			<b-form-group
				label="Triwulan 3"
				label-for="tw3"
				label-class="font-weight-bold"
			>
				<b-form-input
					id="tw3"
					v-model="data.tw3"
					type="number"
					min="0"
					required
				></b-form-input>
			</b-form-group>
			<b-form-group
				label="Triwulan 4"
				label-for="tw4"
				label-class="font-weight-bold"
			>
				<b-form-input
					id="tw4"
					v-model="data.tw4"
					type="number"
					min="0"
					required
				></b-form-input>
			</b-form-group>

			<div class="text-right">
				<b-button type="submit" variant="primary" :disabled="isBusy.submit">
					<b-spinner v-if="isBusy.submit" small></b-spinner>
					Simpan
				</b-button>
			</div>
		</b-form>
	</b-card>
</template>