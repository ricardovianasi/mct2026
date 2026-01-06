// Slider navigations elements
const sliderPrevEl = '.slider-button-prev';
const sliderNextEl = '.slider-button-next';

$(document).ready(() => {
  Fancybox.bind('[data-fancybox]');

  $('.menu-toggle').on('click', function() {
    $('html').toggleClass('mainmenu-active');
  });

  if($(window).width() < 1024) {
    $('#mainmenu .menu-item-has-children .dropdown-toggle').on('click', function(e) {
      e.preventDefault();

      const $this = $(this);
      const subMenu = $this.parents('.menu-item-has-children').find('ul.sub-menu');

      console.log(subMenu);

      if(subMenu.is(':visible')) {
        subMenu.hide(200);
      } else {
        subMenu.show(200);
      }
    })
  }

  const tributeSlider = new Swiper('.home .tribute .swiper', {
    slidesPerView: 1,
    loop: true,
    navigation: {
      nextEl: `.home .tribute ${sliderNextEl}`,
      prevEl: `.home .tribute ${sliderPrevEl}`,
    },
  });

  const banner = new Swiper('.banner-slider .swiper', {
    slidesPerView: 1,
    centeredSlides: true,
    // loop: true,
    spaceBetween: 24,
    pagination: {
      el: '.banner .slider-pagination',
      type: 'bullets',
      clickable: true
    },
    navigation: {
      nextEl: `.banner ${sliderNextEl}`,
      prevEl: `.banner ${sliderPrevEl}`,
    },
  });

  const workshopThumb = new Swiper('.workshop-thumbs .swiper', {
    spaceBetween: 24,
    slidesPerView: 'auto',
    watchSlidesProgress: true,
  });
  const workshop = new Swiper('.workshop-slider-main .swiper', {
    effect: 'fade',
    slidesPerView: 1,
    spaceBetween: 24,
    loop: false,
    pagination: {
      el: '.workshop .slider-pagination',
      type: 'bullets',
      clickable: true,
    },
    navigation: {
      nextEl: `.workshop ${sliderNextEl}`,
      prevEl: `.workshop ${sliderPrevEl}`,
    },
    thumbs: {
      swiper: workshopThumb,
    },
  });

  const art = new Swiper('.art .swiper', {
    slidesPerView: 2,
    spaceBetween: 24,
    loop: false,
    navigation: {
      nextEl: `.art ${sliderNextEl}`,
      prevEl: `.art ${sliderPrevEl}`,
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      }
    }
  });

  const $movies = $('.movie-bgc');
  if($movies.length) {
    $movies.upMovies();
  }

  $('.player').mediaplayer();

  const movieSliders = $('.movie-slider');
  movieSliders.each(function () {
    const sliderId = $(this).attr('id');
    const isAutoplay = $(this).hasClass('movie-autoplay');
    const slidesPerView = $(this).attr('data-slides-per-view');

    const swiperOptions = {
      slidesPerView: (slidesPerView ? slidesPerView : 5),
      spaceBetween: 21,
      navigation: {
        nextEl: `#${sliderId} ${sliderNextEl}`,
        prevEl: `#${sliderId} ${sliderPrevEl}`
      },
      pagination: {
        el: `#${sliderId} .slider-pagination`,
        type: 'bullets',
        clickable: true
      },
      breakpoints: {
        320: {
          slidesPerView: 1.4,
        },
        575: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: (slidesPerView ? slidesPerView : 4),
        }
      }
    };

    if(isAutoplay) {
      swiperOptions.autoplay = {
        delay: 3000
      };
    }

    return new Swiper(`#${sliderId} .movie-items`, swiperOptions);
  });

  const categoriesNames = new Swiper('.home .category-names .swiper', {
    slidesPerView: 5,
    loop: false,
    clickable: true,
    watchSlidesProgress: true,
    spaceBetween: 16,
    breakpoints: {
      320: {
        slidesPerView: 2,
      },
      575: {
        slidesPerView: 3
      },
      768: {
        slidesPerView: 4
      },
      1200: {
        slidesPerView: 5,
      }
    }

  });
  const categoriesItems = new Swiper('.home .category-items .swiper', {
    slidesPerView: 1,
    loop: false,
    thumbs: {
      swiper: categoriesNames,
    },
    navigation: {
      nextEl: `.home .category ${sliderNextEl}`,
      prevEl: `.home .category ${sliderPrevEl}`
    },
    pagination: {
      el: '.home .category .slider-pagination',
      type: 'bullets',
      clickable: true
    },
  });

  const debate = new Swiper('.home .debate .swiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: false,
    pagination: {
      el: '.home .debate .slider-pagination',
      type: 'bullets',
      clickable: true
    },
    navigation: {
      nextEl: `.home .debate ${sliderNextEl}`,
      prevEl: `.home .debate ${sliderPrevEl}`,
    },
  });


  const sections = $('.section-slider');
  sections.each(function () {
    const $current = $(this).find('.swiper');
    const slider = $current.get(0);

    const sectionSlider = new Swiper(slider, {
      slidesPerView: 1,
      loop: true,
      navigation: {
        nextEl: $current.parents('.section').find(`${sliderNextEl}`).get(0),
        prevEl: $current.parents('.section').find(`${sliderPrevEl}`).get(0)
      },
      pagination: {
        el: $current.parents('.section').find('.slider-pagination').get(0),
        type: 'bullets',
        clickable: true
      },
    });
  });

  const timelineThumbs = new Swiper('.home .timeline-years .swiper', {
    slidesPerView: 6,
    loop: false,
    clickable: true,
    watchSlidesProgress: true,
    spaceBetween: 16,
    draggable: false,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 8,
        navigation: false
      },
      575: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 4,
      },
      1200: {
        slidesPerView: 6,
      }
    }

  });
  const timeline = new Swiper('.home .timeline-items .swiper', {
    slidesPerView: 1,
    loop: false,
    thumbs: {
      swiper: timelineThumbs,
    },
    navigation: {
      nextEl: `.home .timeline ${sliderNextEl}`,
      prevEl: `.home .timeline ${sliderPrevEl}`
    },
    pagination: {
      el: '.home .timeline .slider-pagination',
      type: 'bullets',
      clickable: true
    },
  });

  const handleFooterLayout = () => {
    const footer = document.querySelector('.footer');
    const footerDrop = document.querySelector('.footer-drop');
    const drop = document.querySelector('.footer-drop .drop');

    if (!drop || !footerDrop || !footer) {
      if (footerDrop) {
        footerDrop.classList.remove('active');
      }
      return;
    }

    const setDropWidth = () => {
      const footerDropRect = footerDrop.getBoundingClientRect();
      drop.style.width = `${footerDropRect.right}px`;
      drop.style.transform = `translateX(-${footerDropRect.left}px)`;
      footerDrop.classList.add('active');
    }

    const debounce = (func, wait) => {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    setDropWidth();
    const debouncedCalculation = debounce(() => setDropWidth(), 500);
    window.addEventListener('resize', debouncedCalculation);
  };
  handleFooterLayout();


  // Gallery horizontal scroll with drag
  const galleryWrapper = document.querySelector('.gallery-wrapper');
  
  if (galleryWrapper) {
    // Function to center gallery on initial load
    const centerGallery = () => {
      const scrollWidth = galleryWrapper.scrollWidth;
      const clientWidth = galleryWrapper.clientWidth;
      const scrollPosition = (scrollWidth - clientWidth) / 2;
      galleryWrapper.scrollLeft = scrollPosition;
    };
    
    // Center gallery on load
    centerGallery();
    
    // Also center after images load to ensure accurate dimensions
    window.addEventListener('load', () => {
      centerGallery();
    });
    
    // Recenter on window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        centerGallery();
      }, 250);
    });
    
    let isDown = false;
    let startX;
    let scrollLeft;
    let hasMoved = false;
    
    galleryWrapper.addEventListener('mousedown', (e) => {
      isDown = true;
      hasMoved = false;
      startX = e.pageX - galleryWrapper.offsetLeft;
      scrollLeft = galleryWrapper.scrollLeft;
    });
    
    galleryWrapper.addEventListener('mouseleave', () => {
      isDown = false;
      galleryWrapper.classList.remove('dragging');
    });
    
    galleryWrapper.addEventListener('mouseup', () => {
      isDown = false;
      galleryWrapper.classList.remove('dragging');
    });
    
    galleryWrapper.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      
      const x = e.pageX - galleryWrapper.offsetLeft;
      const distance = Math.abs(x - startX);
      
      // Only start dragging if moved more than 5px (prevents accidental drags on clicks)
      if (distance > 5) {
        hasMoved = true;
        galleryWrapper.classList.add('dragging');
        e.preventDefault();
        const walk = (x - startX) * 2; // Scroll speed multiplier
        galleryWrapper.scrollLeft = scrollLeft - walk;
      }
    });
    
    // Prevent click event on links if user was dragging
    galleryWrapper.addEventListener('click', (e) => {
      if (hasMoved) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        hasMoved = false;
      }
    }, true);
    
    // Also prevent default on link clicks during drag
    galleryWrapper.querySelectorAll('a[data-fancybox]').forEach(link => {
      link.addEventListener('click', (e) => {
        if (hasMoved) {
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          return false;
        }
      }, true);
    });
    
    // Touch support for mobile devices
    let touchStartX = 0;
    let touchScrollLeft = 0;
    
    galleryWrapper.addEventListener('touchstart', (e) => {
      touchStartX = e.touches[0].pageX - galleryWrapper.offsetLeft;
      touchScrollLeft = galleryWrapper.scrollLeft;
    }, { passive: true });
    
    galleryWrapper.addEventListener('touchmove', (e) => {
      const x = e.touches[0].pageX - galleryWrapper.offsetLeft;
      const walk = (x - touchStartX) * 2;
      galleryWrapper.scrollLeft = touchScrollLeft - walk;
    }, { passive: true });
  }
  
});
