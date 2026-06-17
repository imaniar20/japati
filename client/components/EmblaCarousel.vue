<template>
  <div class="embla">
    <div class="embla__viewport" ref="emblaViewport">
      <div class="embla__container" :style="`margin-right: -${spacing}px;`">
        <div class="embla__slide" v-for="(slide, index) in slides" :key="index" :style="slideStyle">
          <slot :data="slide">
            <div class="embla__slide__inner">
              {{ index + 1 }}
            </div>
          </slot>
        </div>
      </div>
    </div>

    <div v-if="showDots && dotCount > 1" class="embla__controls">
      <!-- <button class="embla__button embla__button--prev" @click="scrollPrev">
        Prev
      </button> -->
      <div class="embla__dots">
        <button
          v-for="index in dotCount"
          :key="index"
          type="button"
          class="embla__dot"
          :class="{ 'embla__dot--selected': selectedDot === index - 1 }"
          @click="onDotClick(index - 1)"
        ></button>
      </div>
      <!-- <button class="embla__button embla__button--next" @click="scrollNext">
        Next
      </button> -->
    </div>
    <div class="absolute left-0 top-50 transform-left -translate-y-50 -translate-x-5 -mt-4">
      <button class="border-0 size-10 bg-blue-100 rounded-full flex items-center justify-center text-gray-900"
        @click="scrollPrev">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M14.7071 16.7071C14.3166 17.0976 13.6834 17.0976 13.2929 16.7071L9.29289 12.7071C8.90237 12.3166 8.90237 11.6834 9.29289 11.2929L13.2929 7.29289C13.6834 6.90237 14.3166 6.90237 14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L11.4142 12L14.7071 15.2929C15.0976 15.6834 15.0976 16.3166 14.7071 16.7071Z"
            fill="#1E88E5" />
        </svg>
      </button>
    </div>
    <div class="absolute right-0 top-50 transform-right -translate-y-50 translate-x-5 -mt-4">
      <button class="border-0 size-10 bg-blue-100 rounded-full flex items-center justify-center text-gray-900"
        @click="scrollNext">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M9.29289 7.29289C9.68342 6.90237 10.3166 6.90237 10.7071 7.29289L14.7071 11.2929C15.0976 11.6834 15.0976 12.3166 14.7071 12.7071L10.7071 16.7071C10.3166 17.0976 9.68342 17.0976 9.29289 16.7071C8.90237 16.3166 8.90237 15.6834 9.29289 15.2929L12.5858 12L9.29289 8.70711C8.90237 8.31658 8.90237 7.68342 9.29289 7.29289Z"
            fill="#1E88E5" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import EmblaCarousel from 'embla-carousel'

export default {
  name: 'EmblaCarousel',
  props: {
    slidesPerView: {
      type: Number,
      default: 3
    },
    spacing: {
      type: Number,
      default: 16
    },
    slides: {
      type: Array,
      default: () => [1, 2, 3, 4, 5, 6, 7, 8]
    },
    showDots: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      emblaApi: null,
      windowWidth: window.innerWidth,
      // slides: [
      //   'Slide 1',
      //   'Slide 2',
      //   'Slide 3',
      //   'Slide 4',
      //   'Slide 5',
      //   'Slide 6',
      //   'Slide 7',
      //   'Slide 8',
      // ],
      selectedDot: 0,
      currentSlidesPerView: this.slidesPerView,
      scrollSnaps: []
    }
  },
  computed: {
    dotCount() {
      return this.scrollSnaps.length || Math.ceil(this.slides.length / this.currentSlidesPerView)
    },
    slideStyle() {
      const percentage = 100 / this.currentSlidesPerView
      return {
        flex: `0 0 calc(${percentage}% - ${this.spacing}px)`,
        marginRight: `${this.spacing}px`
      }
    }
  },
  methods: {
    handleResize() {
      const previousSlidesPerView = this.currentSlidesPerView

      this.windowWidth = window.innerWidth
      this.calculateSlidesPerView()

      if (previousSlidesPerView !== this.currentSlidesPerView) {
        this.$nextTick(this.initEmbla)
      }
    },
    calculateSlidesPerView() {
      this.currentSlidesPerView = this.windowWidth < 1024
        ? 2
        : this.slidesPerView
    },
    initEmbla() {
      if (this.$refs.emblaViewport) {
        // Destroy previous instance if exists
        if (this.emblaApi) {
          this.emblaApi.off('select', this.onSelect)
          this.emblaApi.destroy()
        }

        this.emblaApi = EmblaCarousel(this.$refs.emblaViewport, {
          slidesToScroll: this.currentSlidesPerView,
          containScroll: 'trimSnaps',
          loop: this.slides.length > this.currentSlidesPerView,
        })

        this.emblaApi.on('select', this.onSelect)
        this.setScrollSnaps()
        this.onSelect()
      }
    },
    scrollPrev() {
      if (this.emblaApi) this.emblaApi.scrollPrev()
    },
    scrollNext() {
      if (this.emblaApi) this.emblaApi.scrollNext()
    },
    onDotClick(index) {
      if (this.emblaApi) {
        this.emblaApi.scrollTo(index)
      }
    },
    onSelect() {
      if (this.emblaApi) {
        this.selectedDot = this.emblaApi.selectedScrollSnap()
      }
    },
    setScrollSnaps() {
      this.scrollSnaps = this.emblaApi ? this.emblaApi.scrollSnapList() : []
    }
  },
  watch: {
    slides() {
      this.$nextTick(this.initEmbla)
    }
  },
  mounted() {
    window.addEventListener('resize', this.handleResize)
    this.calculateSlidesPerView()
    this.initEmbla()
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize)
    if (this.emblaApi) {
      this.emblaApi.off('select', this.onSelect)
      this.emblaApi.destroy()
    }
  }
}
</script>

<style scoped>
.embla {
  /* overflow: hidden; */
  position: relative;
  /* padding: 0 40px; */
}

.embla__viewport {
  overflow: hidden;
  width: 100%;
}

.embla__container {
  display: flex;
  user-select: none;
  -webkit-touch-callout: none;
  -khtml-user-select: none;
  -webkit-tap-highlight-color: transparent;
}

.embla__slide {
  position: relative;
}

.embla__slide__inner {
  padding: 20px;
  background: #f5f5f5;
  border-radius: 8px;
  /* height: 100%; */
  display: flex;
  align-items: center;
  justify-content: center;
}

.embla__controls {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 24px;
}

.embla__button {
  background: #333;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.embla__button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.embla__dots {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
}

.embla__dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: #fff;
  border: 2px solid #1E88E5;
  padding: 0;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.embla__dot--selected {
  background-color: #1E88E5;
}

.absolute {
  position: absolute;
}

.left-0 {
  left: 0;
}

.right-0 {
  right: 0;
}

.top-50 {
  top: 50%;
}

.transform-left {
  transform: translate(-1.25rem, -50%);
}

.transform-right {
  transform: translate(1.25rem, -50%);
}

.-translate-y-50 {
  --tw-translate-y: -50%;
}

.-translate-x-5 {
  --tw-translate-x: -1.25rem;
  /* -translate-x-5 */
}

.-mt-4 {
  margin-top: -1rem;
}

/* Button styles */
.size-10 {
  width: 2.5rem;
  height: 2.5rem;
}

.bg-blue-100 {
  background-color: #dbeafe;
}

.rounded-full {
  border-radius: 9999px;
}

.flex {
  display: flex;
}

.items-center {
  align-items: center;
}

.justify-center {
  justify-content: center;
}

.text-gray-900 {
  color: #111827;
}
</style>
