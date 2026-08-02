// map functions
import { MapID, ZoomLevel } from './constatns';

/**
 * init Google Map function
 * 
 * @param {HTMLElement} mapElement 
 * @param {object} location 
 * @param {object} options 
 * @returns {object} Google Map object
 */
export function initGoogleMap(mapElement, location, configs) {
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
export function getMarkerCard(place) {

  if (!place) return null;
  
  return `<div class="map-card" data-place-id="${place.id}"
    data-map-lat="${place.lat}" data-map-lng="${place.lng}">
    <img class="map-card__img" src="${place.cover}">
    <div class="map-card__footer">
      <h5 class="map-card__title">${place.name}</h5>
      <p class="map-card__text">جدة, ........... , ..........</p>
      <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <div class="rate d-flex align-items-center"><span class="mx-1"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00"/></svg></span><span class="mx-1">4.5</span></div>
          <span class="views mr-3">(2،1k مراجعة)</span>
        </div>
        <a href="/place-details.html" class="btn map-card__btn"><svg width="12" height="12" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.8088 8.80184C4.93123 8.67495 5 8.50288 5 8.32347C5 8.14405 4.93123 7.97198 4.8088 7.8451L1.57628 4.49585L4.8088 1.14661C4.92776 1.019 4.99358 0.848082 4.99209 0.670675C4.9906 0.493269 4.92192 0.323565 4.80085 0.198114C4.67977 0.0726652 4.51598 0.00150681 4.34476 -3.52859e-05C4.17353 -0.00157642 4.00857 0.0666227 3.88541 0.189874L0.191199 4.01749C0.0687744 4.14437 0 4.31644 0 4.49585C0 4.67527 0.0687744 4.84734 0.191199 4.97422L3.88541 8.80184C4.00787 8.92868 4.17394 8.99994 4.34711 8.99994C4.52027 8.99994 4.68634 8.92868 4.8088 8.80184Z" fill="white"/></svg></a>
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
export function getPlaceDetailsTemplate(place) {
  if (!place) return null;

  return `<div class="place-details">
    <h3 class="place-details__title">${place.name}</h3>
    <div class="place-details__img-container">
      <img class="place-details__img" src="${place.cover}">
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
          <span>جدة</span>
        </div>
        <div class="views d-flex align-items-center">
          <span class="mx-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" fill="currentColor" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
              <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
            </svg>
          </span>
          <span class="text mx-1">2.1K</span>
        </div>
      </div>
      <!-- rate -->
      <div class="d-flex align-items-center gap mb-4">
        <div class="review-rate d-flex">
          <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16"><path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
          </svg>
        </div>
        <span>(3.5)</span>
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
    <div class="place-details__body">
      <h4>الوصف</h4>
      <p>هذا النص يمكن أن يتم تركيبه على أي تصميم دون مشكلة فلن يبدو وكأنه نص منسوخ، غير منظم، غير منسق، أو حتى غير مفهوم. لأنه مازال نصاً بديلاً ومؤقتاً.</p>
    </div>
  </div>`;
}

 /**
 * add marker to google map
 * 
 * @param {object} location 
 * @param {object} map 
 * @param {boolean} isActive 
 * @returns new Marker
 */
export function addMarker(location, map, isActive = false) {
  return new google.maps.Marker({
    position: location,
    map: map,
    icon: {
      url: isActive ? 'assets/imgs/marker-open.svg' : 'assets/imgs/marker.svg',
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
export function setCurrentPosition (targetMap) {
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
export function formateQuerySearch(search) {
  const query = new Map();

  if(!search || typeof search !== 'string' ) {
    return query;
  }

  if(search.includes('?')) {
    search = search.replace('?', '');
  }

  let queryArray = search.split('&');

  queryArray.forEach(q => {
    if(q.includes('=')) {
      let queryEntity = q.split('=');
      query.set(queryEntity[0], parseFloat(queryEntity[1]) || queryEntity[1])
    }
  })

  return query;
}