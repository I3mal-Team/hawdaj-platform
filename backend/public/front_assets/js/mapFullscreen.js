
const defaultZoomLevel = 3;
let mockData = [];
const KSACoordinates = { lat: 24.7136, lng: 46.6753 };
const ZoomLevel = 3;
const MapID = '4b1dce4a1905ca17';
const DarkModeMapID = 'd6f14a75929ae696';
const base_url = $('meta[name="base_url"]').attr('content')
var sizeData = 100

function debounce(cb, delay = 2000) {
    let timeout;

    return (...args) => {
        clearTimeout(timeout);

        timeout = setTimeout(() => cb(...args), delay);
    }
}

const mapCanvas = document.getElementById('mapPageCanvas');
const mapOptions = {
    mapId: DarkModeMapID,
    zoomControl: false,
    zoom: defaultZoomLevel
}
let map;

// set current potion
$('#positionBtn').on('click', () => {
    setCurrentPosition(map);
});
/**
 * intMapFullscreen function
 *
 */


/**
 * init map
 */
// window.initMap = intMapFullscreen([]);


// resetControlls function
function resetControlls() {
    $('#googleMapTypeBtn+.map-options__control--menu').removeClass('show');
    $('#googleMapTypeBtn').removeClass('active');
}

/**
 * show search box
 *
 * @param {boolean} noResult
 * @param {boolean} displayOnly
 */
function showSearchBox(noResult = true, displayOnly = false) {

    $('#searchBox').addClass('show');
    $('#searchBoxResetBtn').removeClass('d-none');
    hideMostPopular();

    if (displayOnly) return;

    if ($('#searchBoxResultsContainer').children().length) {
        $('#searchBoxResultsContainer').removeClass('d-none');
    }

    if (!$('#searchBoxResultsContainer').children().length && noResult) {
        $('#searchBoxNoResult').removeClass('d-none');
    } else {
        $('#searchBoxNoResult').addClass('d-none');
    }
}

/**
 * Hide search box
 */
function hideSearchBox() {
    $('#searchBox').removeClass('show');
    $('#searchBoxResetBtn').addClass('d-none');
    $('#searchBoxInput').attr('disabled', false);

    if ($('#searchBoxResultsContainer').children().length) {
        $('#searchBoxNoResult').addClass('d-none');
    }

    resetSearchBox(false);
    showMostPopular();
}

/**
 * show most popular container
 */
function showMostPopular() {
    $('.most-popular-places__container').addClass('show');
}

/**
 * hide most popular container
 */
function hideMostPopular() {
    $('.most-popular-places__container').removeClass('show');
}

/**
 * display place on the Map
 *
 * @param {object} map
 * @param {object} coordinates
 * @param {number} placeID
 * @param {number} zoomLevel
 */
function displayPlaceOnMap(map, coordinates, placeID, zoomLevel = 10) {
    map.panTo(coordinates);

    if (map.zoom !== zoomLevel) {
        map.setZoom(10);
    }

    let place = mockData.find(p => p.id == placeID);

    if (place) {
        let template = getPlaceDetailsTemplate(place);
        $('#searchBoxPlaceDetails').html(template);
        $('#searchBoxPlaceDetails').removeClass('d-none');

        $('#searchBoxResultsContainer').addClass('d-none');
        $('#searchBoxNoResult').addClass('d-none');

        showSearchBox(false, true);
        scrollToSearchBoxTop();
    }
}

/**
 * calling debounce function
 * to get better performance for request call
 * to the server
 * Refrence: https://www.geeksforgeeks.org/debouncing-in-javascript/
 */
const searchOnServer = debounce(() => {
    // AJAX call goes here
    $.ajax({
        url: "{{ route('front.searchPlaces') }}",
        type: "POST",
        data: {
            search: text,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            mockData = response
            console.log('heere');
        }
    });
    // display results into DOM
    displaySearchResults(mockData);
}, 1000 /* you can change delay timeout to save number of requests */);

/**
 * search about places {AJAX Request}
 *
 * @param {string} keyword
 */
function search(keyword) {
    if (keyword) keyword = keyword.trim();

    if (!keyword) return;

    // AJAX call goes here
    $.ajax({
        url: "/searchPlaces",
        type: "POST",
        data: {
            search: keyword,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            mockData = response
            console.log('heere');
            displaySearchResults(mockData);

        }
    });
    // display results into DOM
}

/**
 * display places result
 * into DOM
 *
 * @param {array} places
 */
function displaySearchResults(places) {
    console.log(places);
    // show results count
    let resultsCount = places && places.length ? `
       <div class="d-flex align-items-center gap mb-4">
         <span>عدد النتائج الموجودة </span>
         <span class="badge badge-white">${places.length}</span>
       </div>
       ` : '';

    $('#searchBoxResultsContainer').html(resultsCount);

    places.forEach(place => {
        let placeTemplate = getMarkerCard(place);
        $('#searchBoxResultsContainer').append(placeTemplate);
    });

    $('#searchBoxResultsContainer').removeClass('d-none');
    showSearchBox(false);
    scrollToSearchBoxTop();
}

/**
 * reset search box
 *
 * @param {boolean} resetAll
 * @param {boolean} resetResults
 */
function resetSearchBox(resetAll = true, resetResults = true) {
    map.setZoom(defaultZoomLevel);

    $('#searchBoxInput').val('');
    $('#searchBoxPlaceDetails').html('');
    $('#searchBoxPlaceDetails').addClass('d-none');

    if (resetAll) {
        $('#searchBoxInput').focus();

        $('#searchBoxNoResult').removeClass('d-none');

        if (resetResults) {
            $('#searchBoxResultsContainer').html('');
            $('#searchBoxResultsContainer').addClass('d-none');
        }
    }
}

/**
 * Scroll to top inside search box
 */
function scrollToSearchBoxTop() {
    document.querySelector('#searchBox .search-box__content')
        .scrollTo({
            top: 0,
            behavior: 'smooth'
        });
}




function initGoogleMap(mapElement, location, configs) {
    let defaultConfigs = {
        mapId: MapID,
        center: location,
        zoom: ZoomLevel,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    }

    if (configs) {
        defaultConfigs = Object.assign(defaultConfigs, configs);
    }

    return new google.maps.Map(mapElement, defaultConfigs);
}

/**
 * Format place card on Google Map
 *
 * @param {object} place
 * @returns {HTMLElement | null}
 */
function getMarkerCard(place) {

    if (!place) return null;

    return `<div class="map-card" data-place-id="${place.id}"
      data-map-lat="${place.lat}" data-map-lng="${place.long}">
      <img class="map-card__img" src="${(place.image ? base_url + place.image : base_url + '/front_assets/imgs/zad1.jpg')}">
      <div class="map-card__footer">
        <h5 class="map-card__title">${place.title}</h5>
        <p class="map-card__text">${(place.city ? place.city.name : '..')}, ${(place.region ? place.region.name : '..')}</p>
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="rate d-flex align-items-center"><span class="mx-1"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00"/></svg></span><span class="mx-1">${place.rate}</span></div>
            <span class="views mr-3">(${place.review} مراجعة)</span>
          </div>
        <a href="/${place.type}-details/${place.slug}"  class="btn map-card__btn"><svg width="12" height="12" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.8088 8.80184C4.93123 8.67495 5 8.50288 5 8.32347C5 8.14405 4.93123 7.97198 4.8088 7.8451L1.57628 4.49585L4.8088 1.14661C4.92776 1.019 4.99358 0.848082 4.99209 0.670675C4.9906 0.493269 4.92192 0.323565 4.80085 0.198114C4.67977 0.0726652 4.51598 0.00150681 4.34476 -3.52859e-05C4.17353 -0.00157642 4.00857 0.0666227 3.88541 0.189874L0.191199 4.01749C0.0687744 4.14437 0 4.31644 0 4.49585C0 4.67527 0.0687744 4.84734 0.191199 4.97422L3.88541 8.80184C4.00787 8.92868 4.17394 8.99994 4.34711 8.99994C4.52027 8.99994 4.68634 8.92868 4.8088 8.80184Z" fill="white"/></svg></a>
        </div>
      </div>
    </div>`;
}

/**
 * get place details template
 * to display into search box
 *
 * @param {object} place
 * @returns {HTMLElement | null}
 */
function getPlaceDetailsTemplate(place) {
    if (!place) return null;

    return `
    <div class="place-details">
      <h3 class="place-details__title">${place.title}</h3>
      <div class="place-details__img-container">
      <a href="/${place.type}-details/${place.slug}" >
        <img class="place-details__img" src="${(place.image ? base_url + place.image : base_url + '/front_assets/imgs/zad1.jpg')}">
        </a>

      </div>
      <div class="place-details__header">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="11" r="3" />
                <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
              </svg>
            </span>
            <span>${(place.city ? place?.city?.name : '..')}, ${(place.region ? place?.region?.name : '..')}</span>
          </div>
          <div class="views d-flex align-items-center">
            <span class="mx-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
              </svg>
            </span>
            <span class="text mx-1">(${place.views_num})</span>
          </div>
        </div>
        <!-- rate -->
        <div class="d-flex align-items-center gap mb-4">
          <div class="review-rate d-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>

          </div>
          <span>(${place.rate})</span>
        </div>
        <!-- tags -->
        <div class="d-flex align-items-center flex-wrap gap">
          <span class="badge badge-white">
            سفاري
          </span>
          <span class="badge badge-white">
            مميز
          </span>
        </div>
      </div>
    </div>
    
    `;

    //     <div class="place-details__body">
    //     <h4>الوصف</h4>
    //     <p>${place.description}</p>
    //   </div>
}

/**
* add marker to google map
*
* @param {object} location
* @param {object} map
* @param {boolean} isActive
* @returns new Marker
*/
function addMarker(location, map, isActive = false, icon = null) {
    return new google.maps.Marker({
        position: location,
        map: map,
        icon: {
            url: isActive ? (icon ? icon : '/front_assets/imgs/marker-open.svg') : '/front_assets/imgs/marker.svg',
            scaledSize: new google.maps.Size(35, 35)
        },
    });
}

/**
 * set Current Location
 * address on the map
 * by getting current user lat & lng
 *
 * @param {object} targetMap
 */



function setCurrentPosition(targetMap) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            targetMap.setCenter({ lat, lng });
        });
    }
}

/**
 * format query from href
 *
 * @param {string} search
 * @returns {Map | null}
 */
function formateQuerySearch(search) {
    const query = new Map();

    if (!search || typeof search !== 'string') {
        return query;
    }

    if (search.includes('?')) {
        search = search.replace('?', '');
    }

    let queryArray = search.split('&');

    queryArray.forEach(q => {
        if (q.includes('=')) {
            let queryEntity = q.split('=');
            query.set(queryEntity[0], parseFloat(queryEntity[1]) || queryEntity[1])
        }
    })

    return query;
}

const isRTL = true;

// init header slider
const initHeaderSlider = (element) => {
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
const initServicesCardSlider = (element) => {
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
const navigateToNextSlide = (sliderElement) => {
    $(sliderElement).slick('slickNext');
}

// navigate to prev slide
const navigateToPrevSlide = (sliderElement) => {
    $(sliderElement).slick('slickPrev');
}

// update side number
const updateHeaderSlideNumber = (currSlide) => {
    let count = (currSlide + 1) < 9 ? `0${currSlide + 1}` : (currSlide + 1);

    $('#headerSliderCount').text(count);
}

// init ezba slider navigator
const initEzbaSliderNavigator = (element) => {
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
const updateEzbaSlide = (event, slick, currSlide) => {
    $('#ezbaSlider').carousel(currSlide);
}

// init swalif slider
const initSwalifSlider = (element) => {
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
const initPlcaeCategoriesSlider = (element) => {
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
const initMazadatSlider = (element) => {
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
const initPlaceSliderNavigator = (element) => {
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
const updatePlaceSlide = (event, slick, currSlide) => {
    console.log(currSlide);
    $('#placeSlider').carousel(currSlide);
}

// map fullscreen slider
// const initMostPopularSlider = (element) => {
//     $(element).slick({
//         slidesToShow: 5,
//         slidesToScroll: 1,
//         rtl: isRTL,
//         infinite: true,
//         arrows: true,
//         responsive: [
//             {
//                 breakpoint: 1499,
//                 settings: {
//                     slidesToShow: 4,
//                 }
//             },
//             {
//                 breakpoint: 1280,
//                 settings: {
//                     slidesToShow: 3,
//                 }
//             },
//             {
//                 breakpoint: 900,
//                 settings: {
//                     slidesToShow: 2
//                 }
//             },
//             {
//                 breakpoint: 600,
//                 settings: {
//                     slidesToShow: 1,
//                     centerMode: true,
//                     arrows: false,
//                 }
//             },
//             {
//                 breakpoint: 350,
//                 settings: {
//                     slidesToShow: 1,
//                     centerMode: false,
//                 }
//             },
//         ]
//     });
// }
var m;

function intMapFullscreen(data) {
    const qSearch = formateQuerySearch(window.location.search);
    let currentCoordinates = KSACoordinates;

    if (qSearch.size && qSearch.has('lat') && qSearch.has('lng')) {
        currentCoordinates = {
            lat: qSearch.get('lat'),
            lng: qSearch.get('lng')
        }
    }

    map = initGoogleMap(mapCanvas, currentCoordinates, mapOptions);

    setData(data);

    google.maps.event.addListener(map, 'zoom_changed', function () {
        var zoom = map.getZoom();
        console.log(zoom);
        if (zoom > 5) {
            something();
        }
    });

    var something = (function () {
        var executed = false;
        return function () {
            if (!executed) {
                executed = false; // for one make it true
                sizeData += 100

                $.ajax({
                    url: "/ar/full-map-data",
                    type: "GET",
                    data: {
                        size: sizeData,
                    },
                    success: function (response) {
                        // intMapFullscreen(response);
                        setData(response);
                    }
                });
            }
        };
    })();


    map.addListener('click', function (event) {
        resetControlls();
    });
}

function setData(data) {
    mockData = JSON.parse(data);

    mockData.forEach(place => {
        var place_icon = place.place_icon ? place.place_icon : null
        const marker = addMarker({ lat: place.lat, lng: place.long }, map, true, place_icon);
        const markerContent = getMarkerCard(place);
        const infoWindow = new google.maps.InfoWindow({
            content: markerContent,
            placeID: place.id
        });

        google.maps.event.addListener(marker, 'click', function (event) {
            resetControlls();

            let placeID = infoWindow.placeID;
            let coordinates = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng(),
            }

            displayPlaceOnMap(map, coordinates, placeID);
        });

        google.maps.event.addListener(marker, 'mouseover', function (event) {
            resetControlls();
            infoWindow.open(map, marker);
        });

        google.maps.event.addListener(marker, 'mouseout', function (event) {
            infoWindow.setMap(null);
        });
    });
}


