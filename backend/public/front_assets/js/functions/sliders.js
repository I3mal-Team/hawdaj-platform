const isRTL = true;

// init header slider
export const initHeaderSlider = (element) => {
  $(element).slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: isRTL,
    dots: false,
    arrows: false,
    infinite: true,
    // focusOnSelect: true,
    autoplay: true,
    responsive: [
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          centerMode: true,
        }
      },
    ]
  });
}

// services cards slider
export const initServicesCardSlider = (element) => {
  $(element).slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: isRTL,
    infinite: true,
    // autoplay: true,
    arrows: false,
  });
}

// navigate to next slide
export const navigateToNextSlide = (sliderElement) => {
  $(sliderElement).slick('slickNext');
}

// navigate to prev slide
export const navigateToPrevSlide = (sliderElement) => {
  $(sliderElement).slick('slickPrev');
}

// update side number
export const updateHeaderSlideNumber = (currSlide) => {
  let count = ( currSlide + 1 ) < 9 ? `0${currSlide + 1}` : ( currSlide + 1 );

  $('#headerSliderCount').text(count);
}

// init ezba slider navigator
export const initEzbaSliderNavigator = (element) => {
  $(element).slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: isRTL,
    dots: false,
    arrows: false,
    infinite: true,
    focusOnSelect: true,
    autoplay: true,
    responsive: [
      {
        breakpoint: 1100,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
        }
      },
    ]
  });
}

// update Ezba Slide
export const updateEzbaSlide = (event, slick, currSlide) => {
  $('#ezbaSlider').carousel(currSlide);
}

// init swalif slider
export const initSwalifSlider = (element) => {
  $(element).slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    rtl: isRTL,
    centerMode: true,
    arrows: false,
    responsive: [
      {
        breakpoint: 2000,
        settings: {
          slidesToShow: 3,
          infinite: true
        }
      },
      {
        breakpoint: 1500,
        settings: {
          slidesToShow: 2,
          infinite: true
        }
      },
      {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        infinite: true
      }
    }, 
    {

      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        dots: true
      }

    }]
  });
}

// place Categories slider
export const initPlcaeCategoriesSlider = (element) => {
  $(element).slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: isRTL,
    infinite: true,
    autoplay: true,
    arrows: false,
    responsive: [
      {
        breakpoint: 1280,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 3
        }
      }, 
      {
        breakpoint: 700,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          centerMode: true,
        }
      }, 
      {
        breakpoint: 350,
        settings: {
          slidesToShow: 1,
          centerMode: true,
        }
      }, 
    ]
  });
}

// mazadat slider
export const initMazadatSlider = (element) => {
  $(element).slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: isRTL,
    infinite: true,
    autoplay: true,
    arrows: false,
    responsive: [
      {
        breakpoint: 1400,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3,
          centerMode: true,
        }
      }, 
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          centerMode: true,
        }
      },
      {
        breakpoint: 580,
        settings: {
          slidesToShow: 1,
          centerMode: true,
        }
      }, 
      {
        breakpoint: 350,
        settings: {
          slidesToShow: 1,
          centerMode: true,
        }
      }, 
    ]
  });
}

// init place skider navigation
export const initPlaceSliderNavigator = (element) => {
  let screenWidth = window.innerWidth;

  $(element).slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: screenWidth <= 576,
    vertical: screenWidth > 576,
    arrows: false,
    // responsive: [
    //   {
    //     breakpoint: 2000,
    //     settings: {
    //       slidesToShow: 3,
    //       infinite: true
    //     }
    //   },
    //   {
    //     breakpoint: 1500,
    //     settings: {
    //       slidesToShow: 2,
    //       infinite: true
    //     }
    //   },
    //   {
    //   breakpoint: 767,
    //   settings: {
    //     slidesToShow: 1,
    //     infinite: true
    //   }
    // }, 
    // {

    //   breakpoint: 600,
    //   settings: {
    //     slidesToShow: 1,
    //     dots: true
    //   }

    // }]
  });
}

// update Place Slide
export const updatePlaceSlide = (event, slick, currSlide) => {
  console.log(currSlide);
  $('#placeSlider').carousel(currSlide);
}

// map fullscreen slider
export const initMostPopularSlider = (element) => {
  $(element).slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: isRTL,
    infinite: true,
    arrows: true,
    responsive: [
      {
        breakpoint: 1499,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 1280,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: 2
        }
      }, 
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          centerMode: true,
          arrows: false,
        }
      },
      {
        breakpoint: 350,
        settings: {
          slidesToShow: 1,
          centerMode: false,
        }
      }, 
    ]
  });
}