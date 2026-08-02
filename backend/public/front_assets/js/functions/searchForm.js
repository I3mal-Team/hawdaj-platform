const typingNoteTemplate = `
    <li class="p-2 text-center">
      اكتب اسم المكان لبدء البحث
    </li>
  `;

// toggle results menu
export function toggleResultsMenu(element, status) {
  if(status) {
    $($(element).parent().next('.search-form__results')).addClass('active');
    // $('body').addClass('overflow-hidden');
    return;
  }

  $(element).val('');
  $($(element).parent().next('.search-form__results')).removeClass('active');
  $('.search-form__results').html(typingNoteTemplate);
  // $('body').removeClass('overflow-auto');
}

const results = [
  {
    title: 'اسم المكان',
    img: 'assets/imgs/hero.jpg',
    url: '/place-details.html',
    desc: 'هذا النص يمكن أن يتم تركيبه على أي تصميم دون مشكلة فلن يبدو وكأنه نص منسوخ، غير منظم، غير منسق، أو حتى غير مفهوم. لأنه مازال نصاً بديلاً ومؤقتاً'
  },
  {
    title: 'اسم المكان',
    img: 'assets/imgs/hero.jpg',
    url: '/place-details.html',
    desc: 'هذا النص يمكن أن يتم تركيبه على أي تصميم دون مشكلة فلن يبدو وكأنه نص منسوخ، غير منظم، غير منسق، أو حتى غير مفهوم. لأنه مازال نصاً بديلاً ومؤقتاً'
  },
  {
    title: 'اسم المكان',
    img: 'assets/imgs/hero.jpg',
    url: '/place-details.html',
    desc: 'هذا النص يمكن أن يتم تركيبه على أي تصميم دون مشكلة فلن يبدو وكأنه نص منسوخ، غير منظم، غير منسق، أو حتى غير مفهوم. لأنه مازال نصاً بديلاً ومؤقتاً'
  },
  {
    title: 'اسم المكان',
    img: 'assets/imgs/hero.jpg',
    url: '/place-details.html',
    desc: 'هذا النص يمكن أن يتم تركيبه على أي تصميم دون مشكلة فلن يبدو وكأنه نص منسوخ، غير منظم، غير منسق، أو حتى غير مفهوم. لأنه مازال نصاً بديلاً ومؤقتاً'
  }
]

// get result template
const getResultTemplate = (result) => {
  return `
  <li class="search-form__results-card">
    <a class="d-flex align-items-center" href="${ result.url }">
      <div>
          <img src="${ result.img }" alt="${ result.title }">
      </div>
      <div>
          <h4 class="head">${ result.title }</h4>
          <p class="mb-0">
              ${ result.desc }
          </p>
      </div>
    </a>
  </li>
  `;
}

const getLoadingTemplate = () => {
  return `
  <li class="search-form__results-card search-form__results-loading">
    <div class="d-flex align-items-center">
      <div>
      <div class="search-form__results-loading-img boading-style"></div>
      </div>
      <div class="w-100">
          <h4 class="search-form__results-loading-head boading-style"></h4>
          <p class="search-form__results-loading-desc boading-style mb-0"></p>
      </div>
    </div>
  </li>
  `;
} 

// strat searching
export function search() {
  let keyword = $(this).val();

  if(keyword) {
    keyword = keyword.trim();
  }
  
  if(!keyword) {
    $('.search-form__results').html(typingNoteTemplate);
    return;
  }

  $('.search-form__results').html('');
  for (let i = 0; i < 3; i++) {
    $('.search-form__results').append(getLoadingTemplate());
  }

  setTimeout(() => {
    $('.search-form__results').html('');
    results.forEach(result => {
      let template = getResultTemplate(result);

      $('.search-form__results').append(template);
    });
  }, 2000);
}