<template>
  <div>
    <sidebar />

    <div class="page-container">
      <navbar />

      <main class='main-content bgc-grey-100'>

        <div id='mainContent'>
          <breadcrumb />
          <Nuxt />
        </div>
      </main>

      <footer class="bdT ta-c py-4 fsz-sm c-grey-600">
        <span>Copyright © {{ new Date().getFullYear() }} Pemerintah Kabupaten Kuningan</span>
      </footer>
    </div>
  </div>
</template>

<script>
import Navbar from "~/components/Navbar";
import Sidebar from "~/components/Sidebar";
import Breadcrumb from "~/components/global/Breadcrumb";
import * as $ from 'jquery';
import PerfectScrollbar from 'perfect-scrollbar';
import { mapGetters } from 'vuex'

export default {
  head: {
    bodyAttrs: {
      class: 'app'
    }
  },
  components: {
    Navbar,
    Sidebar,
    Breadcrumb,
  },
  // computed: mapGetters({
  //   authenticated: 'auth/check'
  // }),
  mounted() {
    // ------------------------------------------------------
    // @Popover
    // ------------------------------------------------------

    $('[data-toggle="popover"]').popover();

    // ------------------------------------------------------
    // @Tooltips
    // ------------------------------------------------------

    $('[data-toggle="tooltip"]').tooltip();

    // ------------------------------------------------------
    // @scrollbar
    // ------------------------------------------------------
    const scrollables = $('.scrollable');
    if (scrollables.length > 0) {
      scrollables.each((index, el) => {
        new PerfectScrollbar(el);
      });
    }

    // ------------------------------------------------------
    // @search
    // ------------------------------------------------------
    $('.search-toggle').on('click', e => {
      $('.search-box, .search-input').toggleClass('active');
      $('.search-input input').focus();
      e.preventDefault();
    });

    // ------------------------------------------------------
    // @sidebar
    // ------------------------------------------------------
    // Sidebar links
    $('.sidebar .sidebar-menu li a').on('click', function () {
      const $this = $(this);

      if ($this.parent().hasClass('open')) {
        $this
          .parent()
          .children('.dropdown-menu')
          .slideUp(200, () => {
            $this.parent().removeClass('open');
          });
      } else {
        $this
          .parent()
          .parent()
          .children('li.open')
          .children('.dropdown-menu')
          .slideUp(200);

        $this
          .parent()
          .parent()
          .children('li.open')
          .children('a')
          .removeClass('open');

        $this
          .parent()
          .parent()
          .children('li.open')
          .removeClass('open');

        $this
          .parent()
          .children('.dropdown-menu')
          .slideDown(200, () => {
            $this.parent().addClass('open');
          });
      }
    });

    // Sidebar Activity Class
    const sidebarLinks = $('.sidebar').find('.sidebar-link');

    sidebarLinks
      .each((index, el) => {
        $(el).removeClass('active');
      })
      .filter(function () {
        const href = $(this).attr('href');
        const pattern = href[0] === '/' ? href.substr(1) : href;
        return pattern === (window.location.pathname).substr(1);
      })
      .addClass('active');

    // Sidebar Toggle
    $('.sidebar-toggle').on('click', e => {
      $('.app').toggleClass('is-collapsed');
      e.preventDefault();
    });

    /**
     * Wait untill sidebar fully toggled (animated in/out)
     * then trigger window resize event in order to recalculate
     * masonry layout widths and gutters.
     */
    $('#sidebar-toggle').click(e => {
      e.preventDefault();
      setTimeout(() => {
        window.dispatchEvent(window.EVENT);
      }, 300);
    });

    // ------------------------------------------------------
    // @utils
    // ------------------------------------------------------
    // ------------------------------------------------------
    // @Window Resize
    // ------------------------------------------------------

    /**
     * NOTE: Register resize event for Masonry layout
     */
    const EVENT = document.createEvent('UIEvents');
    window.EVENT = EVENT;
    EVENT.initUIEvent('resize', true, false, window, 0);


    window.addEventListener('load', () => {
      /**
       * Trigger window resize event after page load
       * for recalculation of masonry layout.
       */
      window.dispatchEvent(EVENT);
    });

    // ------------------------------------------------------
    // @External Links
    // ------------------------------------------------------

    // Open external links in new window
    $('a')
      .filter('[href^="http"], [href^="//"]')
      .not(`[href*="${window.location.host}"]`)
      .attr('rel', 'noopener noreferrer')
      .attr('target', '_blank');

    // ------------------------------------------------------
    // @Resize Trigger
    // ------------------------------------------------------

    // Trigger resize on any element click
    document.addEventListener('click', () => {
      window.dispatchEvent(window.EVENT);
    });
  },
};
</script>
