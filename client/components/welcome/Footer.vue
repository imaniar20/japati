<script>
export default {
  name: 'WelcomeFooter'
}
</script>

<template>
  <footer>
    <div class="footer">
      <img src="~/assets/images/japati.png" alt="Logo Japati" style="height:40px">
      <b-row>
        <b-col md="6">
          <div class="flex align-items-start gap-1.5">
            <img src="/icons/location.svg" alt="">
            <div class="d-flex flex-column -mt-1">
              <p class="text-sm text-white font-bold leading-6 mb-2">
                Organisasi Pemerintah Kabupaten Kuningan
              </p>
              <p class="text-sm text-white font-lato leading-6 m-0">
                Jl. Dr. Ir. Soekarno, Winduherang <br>
                Kec. Cigugur, Kabupaten Kuningan <br>
                Jawa Barat 45552
              </p>
            </div>
          </div>
        </b-col>
        <b-col md="2">
          <div class="flex align-items-start gap-1.5">
            <img src="/icons/sms.svg" alt="">
            <div class="d-flex flex-column -mt-1">
              <p class="text-sm text-white font-bold leading-6 mb-2">Surel</p>
              <a href="mailto:info@kuningankab.go.id"
                class="text-sm text-white font-lato leading-6 m-0">info@kuningankab.go.id</a>
            </div>
          </div>
        </b-col>
        <b-col md="2">
          <div class="flex align-items-start gap-1.5">
            <img src="/icons/call-calling.svg" alt="">
            <div class="d-flex flex-column -mt-1">
              <p class="text-sm text-white font-bold leading-6 mb-2">Telepon</p>
              <a href="tel:0232-871142" class="text-sm text-white font-lato leading-6 m-0">(0232)-871142</a>
            </div>
          </div>
        </b-col>
        <b-col md="2">
          <div class="flex align-items-start gap-1.5">
            <img src="/icons/global.svg" alt="">
            <div class="d-flex flex-column -mt-1">
              <p class="text-sm text-white font-bold leading-6 mb-2">Media Sosial</p>
              <div class="d-flex">
                <a href="https://instagram.com/kominfokuningan" target="_blank">
                  <div class="box-sosmed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="448" height="512" viewBox="0 0 448 512">
                      <path fill="white"
                        d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9S287.7 141 224.1 141m0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7s74.7 33.5 74.7 74.7s-33.6 74.7-74.7 74.7m146.4-194.3c0 14.9-12 26.8-26.8 26.8c-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8s26.8 12 26.8 26.8m76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9 0c-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9c1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0c35.9-1.7 67.7-9.9 93.9-36.2c26.2-26.2 34.4-58 36.2-93.9c2.1-37 2.1-147.8 0-184.8M398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6c-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6c29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6c11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1" />
                    </svg>
                  </div>
                </a>
                <a href="https://web.facebook.com/KuninganPemkab" target="_blank">
                  <div class="box-sosmed ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="448" height="512" viewBox="0 0 448 512">
                      <path fill="white" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06H297V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                    </svg>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
    </div>
    <div class="container-footer">
      <div class="inner-container">
        <p class="text-item m-0">
          Copyright © {{ new Date().getFullYear() }} Organisasi Pemerintah Kabupaten Kuningan. All Right Reserved
        </p>
        <div class="flex-group">
          <p class="text-item m-0">Made with</p>
          <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M2.99994 3.49994C5.33232 2.33376 7.24994 3.74994 7.99994 4.74994C8.74994 3.74994 10.6676 2.33376 12.9999 3.49994C15.9999 4.99994 15.2499 8.74995 12.9999 11C11.9021 12.0978 9.96854 14.0313 8.55434 15.1068C8.22762 15.3552 7.7807 15.3441 7.46131 15.0863C6.16495 14.04 4.08246 12.0825 2.99994 10.9999C0.749931 8.74994 -6.34193e-05 4.99994 2.99994 3.49994Z"
              fill="#F8312F" />
            <path
              d="M7.99992 4.74993V6.27452C8.63476 4.93245 10.2126 3.14019 12.8289 3.41867C10.5741 2.40305 8.73151 3.77448 7.99992 4.74993Z"
              fill="#CA0B4A" />
            <path
              d="M5.97278 3.2684C5.13068 2.97497 4.1125 2.94366 2.99994 3.49994C-6.34193e-05 4.99994 0.749931 8.74994 2.99994 10.9999C4.08246 12.0825 6.16495 14.04 7.46131 15.0863C7.7807 15.3441 8.22762 15.3552 8.55434 15.1068C8.68144 15.0101 8.81273 14.9065 8.94724 14.7973C7.5199 13.7261 5.51677 12.038 4.42949 11.0662C1.95109 8.85094 1.12497 5.15888 4.42949 3.68206C4.96639 3.44212 5.48335 3.31321 5.97278 3.2684Z"
              fill="#CA0B4A" />
            <ellipse cx="11.7386" cy="6.79681" rx="1.41777" ry="2.39062" transform="rotate(30 11.7386 6.79681)"
              fill="#F37366" />
          </svg>
          <p class="text-item m-0">For Kuningan</p>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
/* Footer container */
footer {
  background-color: #1E88E5;
}

/* Inner container */
footer>.footer {
  max-width: 80rem;
  /* max-w-7xl */
  margin-left: auto;
  /* mx-auto */
  margin-right: auto;
  /* mx-auto */
  padding-top: 2rem;
  /* py-8 */
  padding-bottom: 2rem;
  /* py-8 */
}

/* Space between child elements */
footer>.footer>*+* {
  margin-top: 2rem;
  /* space-y-8 */
}

/* Grid layout */
.grid {
  display: grid;
}

.grid-cols-5 {
  grid-template-columns: repeat(5, minmax(0, 1fr));
}

.gap-6 {
  gap: 1.5rem;
  /* 24px */
}

.px-4 {
  padding-left: 1rem;
  /* 16px */
  padding-right: 1rem;
  /* 16px */
}

/* Responsive padding */
@media (min-width: 1024px) {
  .lg\\:px-0 {
    padding-left: 0;
    padding-right: 0;
  }
}

/* Column span */
.col-span-5 {
  grid-column: span 5 / span 5;
}

/* Responsive column span */
@media (min-width: 1024px) {
  .lg\\:col-span-2 {
    grid-column: span 2 / span 2;
  }
}

/* Flex container */
.flex {
  display: flex;
}

.gap-1\.5 {
  gap: 0.375rem;
  /* 6px */
}

/* Text container */
.space-y-2>*+* {
  margin-top: 0.5rem;
  /* 8px */
}

.-mt-1 {
  margin-top: -0.25rem;
  /* -4px */
}

/* Text styles */
.text-sm {
  font-size: 0.875rem;
  /* 14px */
  line-height: 1.5rem;
  /* 24px */
}

.text-white {
  color: #ffffff;
}

.font-bold {
  font-weight: 700;
}

.leading-6 {
  line-height: 1.5rem;
  /* 24px */
}

.font-lato {
  font-family: 'Lato', sans-serif;
}

.box-sosmed {
  width: 1.5rem;
  /* 24px (size-6) */
  height: 1.5rem;
  /* 24px (size-6) */
  display: flex;
  /* flex */
  align-items: center;
  /* items-center */
  justify-content: center;
  /* justify-center */
}

.container-footer {
  border-top: 1px solid #3b82f6;
  /* border-t border-blue-500 */
}

.inner-container {
  display: flex;
  flex-direction: column;
  /* flex-col on mobile */
  align-items: center;
  justify-content: space-between;
  max-width: 80rem;
  /* max-w-7xl (80rem = 1280px) */
  margin-left: auto;
  margin-right: auto;
  padding: 1rem;
  /* py-4 px-4 */
  font-family: 'Lato', sans-serif;
  /* font-lato */
}

.text-item {
  font-size: 0.875rem;
  /* text-sm (14px) */
  color: white;
  line-height: 1.5rem;
  /* leading-6 (24px) */
}

.flex-group {
  align-self: flex-start;
  /* self-start */
  display: flex;
  align-items: center;
  gap: 0.25rem;
  /* gap-1 (4px) */
}

/* Media query for lg breakpoint (typically 1024px) */
@media (min-width: 1024px) {
  .inner-container {
    flex-direction: row;
    /* lg:flex-row */
    padding-left: 0;
    /* lg:px-0 */
    padding-right: 0;
  }
}
</style>