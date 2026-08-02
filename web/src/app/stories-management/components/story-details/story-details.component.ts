// Modules
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ActivatedRoute, RouterModule } from '@angular/router';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { PaginatorModule } from 'primeng/paginator';
import { CarouselModule } from 'primeng/carousel';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
// Components
import { ReviewEventSliderComponent } from 'src/app/shared/components/review-event-slider/review-event-slider.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { RateComponent } from 'src/app/components/events/components/rate/rate.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { DetailsBannerComponent } from '../details-banner/details-banner.component';
import { ShareSocialComponent } from "../../../Common/component/share-social/share-social.component";
import { ListSliderComponent } from 'src/app/Common/component/list-card/list-slider/list-slider.component';
import { SliderComponent } from "../slider/slider.component";
import { StoriesService } from '../../services';
import { StoryContentComponent } from "../story-content/story-content.component";
import { MediaViewerComponent } from "../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-story-details',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    CarouselModule,
    RouterModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    // Components
    ReviewEventSliderComponent,
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    FooterComponent,
    NewFooterComponent,
    DetailsBannerComponent,
    StoryDetailsComponent,
    ListSliderComponent,
    // Directives
    LazyLoadSectionDirective,
    ShareSocialComponent,
    SliderComponent,
    StoryContentComponent,
    MediaViewerComponent
  ],
  templateUrl: './story-details.component.html',
  styleUrls: ['./story-details.component.scss']
})
export class StoryDetailsComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  isLoading: boolean = false;
  storyId: any;
  storyDetails: any;

  featuredStories: any = [];
  isLoadingFeaturedStories: boolean = false;
  page: any = 1;
  perPage: any = 12; copied = false;
  reviews: any = [];
  isLoadingReviews: boolean = false;

  storiesResponsiveOptions: any = [
    {
      breakpoint: '1800px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '1024px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 1,
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1,
    },
  ];

  responsiveOptions = [
    {
      breakpoint: '1024px',
      numVisible: 3,
      numScroll: 3
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 2
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1
    }
  ];

  fullUrl: any = null;
  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private storiesService: StoriesService,
    private dialogService: DialogService,
    private publicService: PublicService,
    private alertsService: AlertsService
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.activatedRoute.params.subscribe(params => {
      this.storyId = params['id'];
      if (this.storyId) {
        this.getStoryDetails();
        // this.fullUrl = environment.publicUrl + '/stories/' + this.storyId;
        this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
      }
    });
    this.getFeaturedStories();
  }

  getStoryDetails(): void {
    this.isLoading = true;
    this.storiesService?.getStoryById(this.storyId)?.subscribe(
      (res: any) => this.handleStoryDetailsResponse(res),
      (err: any) => this.handleStoryDetailsError(err)
    );
  }
  private handleStoryDetailsResponse(res: any): void {
    if (res?.code === 200) {
      this.storyDetails = res?.data;
      // this.storyDetails = {
      //   "id": 12,
      //   "slug": "building-the-kaaba",
      //   "image": "uploads/swalefs/IMG_0132.webp",
      //   "type": "swalef",
      //   "active": 1,
      //   "created_at": "2025-05-20T11:13:58.000000Z",
      //   "updated_at": "2025-05-28T16:45:42.000000Z",
      //   "featured": 1,
      //   "rate": 0,
      //   "review": 0,
      //   "title": "بناء الكعبة",
      //   "description": "قصة نبي الله إبراهيم عليه السلام في بناء الكعبة.",
      //   "content": "في أرضٍ جرداء، لا زرع فيها ولا ماء، وعلى سفوح جبال مكة، بدأت إحدى أعظم قصص التاريخ… قصة بناء الكعبة، أول بيتٍ وُضع للناس لعبادة الله.\r\n\r\nقبل أن تُبنى الكعبة، أمر الله نبيه إبراهيم عليه السلام أن يرحل بزوجته هاجر وابنه الرضيع إسماعيل إلى وادٍ مقفر، لا يسكنه أحد. امتثل إبراهيم للأمر الإلهي، وتركهما هناك، ثم رفع يديه بالدعاء:\r\n\r\n“رَبَّنَا إِنِّي أَسْكَنْتُ مِنْ ذُرِّيَّتِي بِوَادٍ غَيْرِ ذِي زَرْعٍ عِندَ بَيْتِكَ الْمُحَرَّمِ…”\r\n(إبراهيم: 37)\r\n\r\nنفد الماء، وبكت هاجر، فركضت بين الصفا والمروة تبحث عن قطرة ماء، حتى تفجّر من تحت قدم إسماعيل ماء زمزم، ليبدأ النبض في هذه الأرض، وتبدأ القبائل في الاستيطان، وعلى رأسها قبيلة جُرهم.\r\n\r\nومرت السنين، وكبر إسماعيل، وعاد إبراهيم لزيارة أهله. عندها أوحى الله إليه أن يبني بيتًا يعبد فيه وحده لا شريك له. فوقف إبراهيم وابنه إسماعيل، واستعدّا لرفع قواعد البيت.\r\n\r\nبيدٍ مليئة بالإيمان، بدأ إبراهيم عليه السلام يضع الحجارة، وإسماعيل يناوله، وهما يرددان دعاءً خلدته الآيات:\r\n\r\n“وَإِذْ يَرْفَعُ إِبْرَاهِيمُ الْقَوَاعِدَ مِنَ الْبَيْتِ وَإِسْمَاعِيلُ: رَبَّنَا تَقَبَّلْ مِنَّا إِنَّكَ أَنتَ السَّمِيعُ الْعَلِيمُ.”\r\n(البقرة: 127)\r\n\r\nكلما ارتفع البناء، احتاج إبراهيم إلى ما يساعده على بلوغ الأعلى، فجاءه حجرٌ ووقف عليه، فترك عليه أثر قدميه. لا يزال هذا الحجر حتى اليوم محفوظًا بجوار الكعبة، يُعرف باسم مقام إبراهيم.\r\n\r\nوحين اكتمل بناء الكعبة، دعا إبراهيم ربه دعوات عظيمة:\r\n\r\n“رَبِّ اجْعَلْ هَذَا بَلَدًا آمِنًا، وَارْزُقْ أَهْلَهُ مِنَ الثَّمَرَاتِ…”\r\n(البقرة: 126)\r\n\r\nومن تلك اللحظة، أصبح البيت الحرام قبلةً للمسلمين، ومهوى لأفئدة البشر من كل بقاع الأرض.\r\n\r\nالكعبة التي بناها إبراهيم وابنه، ليست فقط بناءً من الحجارة، بل هي رمزٌ للطاعة المطلقة، والتسليم لله، والعبادة الخالصة.",

      //   "categories": [
      //     { id: 1, title: 'تاريخي', icon: 'assets/images-v2/pages/Home/quick-search/new-location-icon.webp' },
      //     { id: 2, title: 'ديني', icon: 'assets/images-v2/pages/Home/quick-search/new-search-icon.webp' }
      //   ],
      //   "timePeriod": "تُشير المصادر التاريخية إلى أن بناء الكعبة تم في زمن نبي الله إبراهيم عليه السلام، الذي يُقدّر أن يكون في القرن العشرين قبل الميلاد.",
      //   "location": "تقع الكعبة المشرفة في مكة المكرمة، المملكة العربية السعودية.",
      //   "mainCharacters": ['إبراهيم عليه السلام: النبي الذي أمره الله ببناء الكعبة.', 'إسماعيل عليه السلام: ابن إبراهيم الذي شاركه في بناء الكعبة.'],
      //   "storyImportance": "تُعد قصة بناء الكعبة من أهم القصص في التاريخ الإسلامي، حيث تمثل بداية العبادة الموحدة لله في الأرض، وتُعتبر الكعبة قبلة المسلمين في صلاتهم، مما يجعلها مركزًا روحيًا وتاريخيًا هامًا.",
      //   "relevanceToPresent": "الكعبة المشرفة ما زالت قائمة حتى اليوم في مكة المكرمة، وتُعد مقصدًا للمسلمين من جميع أنحاء العالم لأداء مناسك الحج والعمرة.",
      //   "address": "https://maps.app.goo.gl/84Qan7iuM2a9uSgj7",
      //   "galleries": [
      //     {
      //       "id": 18273,
      //       "file": "uploads/gallery/images.jpeg",
      //       "type": "guides",
      //       "mime_type": "image"
      //     },
      //     {
      //       "id": 18274,
      //       "file": "uploads/gallery/dummy.pdf",
      //       "type": "guides",
      //       "mime_type": "pdf"
      //     },
      //     {
      //       "id": 18275,
      //       "file": "uploads/gallery/SampleVideo_1280x720_2mb.mp4",
      //       "type": "guides",
      //       "mime_type": "video"
      //     }
      //   ],
      //   "source": "'القرآن الكريم، سورة البقرة (الآية 127).'",
      //   "audioStory": {
      //     title: 'بناء الكعبة',
      //     link: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"
      //   },

      //   "translations": [
      //     {
      //       "id": 41,
      //       "swalef_id": 12,
      //       "locale": "ar",
      //       "title": "بناء الكعبة",
      //       "description": "قصة نبي الله إبراهيم عليه السلام في بناء الكعبة.",
      //       "content": "في أرضٍ جرداء، لا زرع فيها ولا ماء، وعلى سفوح جبال مكة، بدأت إحدى أعظم قصص التاريخ… قصة بناء الكعبة، أول بيتٍ وُضع للناس لعبادة الله.\r\n\r\nقبل أن تُبنى الكعبة، أمر الله نبيه إبراهيم عليه السلام أن يرحل بزوجته هاجر وابنه الرضيع إسماعيل إلى وادٍ مقفر، لا يسكنه أحد. امتثل إبراهيم للأمر الإلهي، وتركهما هناك، ثم رفع يديه بالدعاء:\r\n\r\n“رَبَّنَا إِنِّي أَسْكَنْتُ مِنْ ذُرِّيَّتِي بِوَادٍ غَيْرِ ذِي زَرْعٍ عِندَ بَيْتِكَ الْمُحَرَّمِ…”\r\n(إبراهيم: 37)\r\n\r\nنفد الماء، وبكت هاجر، فركضت بين الصفا والمروة تبحث عن قطرة ماء، حتى تفجّر من تحت قدم إسماعيل ماء زمزم، ليبدأ النبض في هذه الأرض، وتبدأ القبائل في الاستيطان، وعلى رأسها قبيلة جُرهم.\r\n\r\nومرت السنين، وكبر إسماعيل، وعاد إبراهيم لزيارة أهله. عندها أوحى الله إليه أن يبني بيتًا يعبد فيه وحده لا شريك له. فوقف إبراهيم وابنه إسماعيل، واستعدّا لرفع قواعد البيت.\r\n\r\nبيدٍ مليئة بالإيمان، بدأ إبراهيم عليه السلام يضع الحجارة، وإسماعيل يناوله، وهما يرددان دعاءً خلدته الآيات:\r\n\r\n“وَإِذْ يَرْفَعُ إِبْرَاهِيمُ الْقَوَاعِدَ مِنَ الْبَيْتِ وَإِسْمَاعِيلُ: رَبَّنَا تَقَبَّلْ مِنَّا إِنَّكَ أَنتَ السَّمِيعُ الْعَلِيمُ.”\r\n(البقرة: 127)\r\n\r\nكلما ارتفع البناء، احتاج إبراهيم إلى ما يساعده على بلوغ الأعلى، فجاءه حجرٌ ووقف عليه، فترك عليه أثر قدميه. لا يزال هذا الحجر حتى اليوم محفوظًا بجوار الكعبة، يُعرف باسم مقام إبراهيم.\r\n\r\nوحين اكتمل بناء الكعبة، دعا إبراهيم ربه دعوات عظيمة:\r\n\r\n“رَبِّ اجْعَلْ هَذَا بَلَدًا آمِنًا، وَارْزُقْ أَهْلَهُ مِنَ الثَّمَرَاتِ…”\r\n(البقرة: 126)\r\n\r\nومن تلك اللحظة، أصبح البيت الحرام قبلةً للمسلمين، ومهوى لأفئدة البشر من كل بقاع الأرض.\r\n\r\nالكعبة التي بناها إبراهيم وابنه، ليست فقط بناءً من الحجارة، بل هي رمزٌ للطاعة المطلقة، والتسليم لله، والعبادة الخالصة."
      //     },
      //     {
      //       "id": 42,
      //       "swalef_id": 12,
      //       "locale": "en",
      //       "title": "Building the Kaaba",
      //       "description": "The story of the Prophet of God, Abraham, peace be upon him, in building the Kaaba.",
      //       "content": "In a barren land, in which there is no planting or water, and on the foothills of the mountains of Mecca, one of the greatest stories of history began ... the story of building the Kaaba, the first house to be placed for people to worship God.\r\n\r\nBefore the Kaaba was built, God commanded his Prophet Abraham, peace be upon him, to leave his wife, Hajar and his baby, Ismail, to a clinic valley, which no one lives in. Abraham complied with the divine command, left them there, then raised his hands with supplication:\r\n\r\n“Our Lord, I dwelt from my offspring, with a place that is not overburdened with your forbidden house ...”\r\n(Ibrahim: 37)\r\n\r\nThe water ran out, and Hajar cried, and it ran between Al -Safa and Al -Marwah looking for a drop of water, until he exploded from under the foot of Ismail Zamzam water, so that the pulse began in this land, and the tribes begin to settle, on top of which is the tribe of Jarrah.\r\n\r\nThe years passed, Ismail grew up, and Ibrahim returned to visit his family. Then God revealed to him to build a house in which he will be worshiped alone. Ibrahim and his son Ismail stood up, and prepared to raise the rules of the house.\r\n\r\nWith a hand full of faith, Abraham, peace be upon him, began to put stones, and Ismail is making him, and they repeat a supplication that immortalized the verses:\r\n\r\nAnd when Abraham raises Al -Qawasad from the house and Issahil: our Lord accepts from us, for you are the most knowledgeable. \"\r\n(Al -Baqarah: 127)\r\n\r\nThe higher the construction, the more Abraham needed what helps him to reach the highest, so a stone came and stood on it, so he left the effect of his feet. This stone is still preserved next to the Kaaba, known as the Maqam of Ibrahim.\r\n\r\nAnd when the construction of the Kaaba was completed, Ibrahim called his Lord great invitations:\r\n\r\n\"Lord, make this a safe country, and give his people from the fruits ...\"\r\n(Al -Baqarah: 126)\r\n\r\nFrom that moment, the Sacred House has become a kiss for Muslims, and it is fascinated by the hearts of human beings from all parts of the earth.\r\n\r\nThe Kaaba built by Abraham and his son, is not only a construction of stones, but it is a symbol of absolute obedience, surrender to God, and pure worship."
      //     },
      //     {
      //       "id": 43,
      //       "swalef_id": 12,
      //       "locale": "ru",
      //       "title": "Построение Каабы",
      //       "description": "История Пророка Божьего, Авраама, мир на него, в строительстве Каабы.",
      //       "content": "В бесплодной земле, в которой нет посадки или воды, и на предгорьях горов Мекки началась одна из величайших историй истории ... история о строительстве Каабы, первом доме, который будет помещен для людей, чтобы поклоняться Богу.\r\n\r\nДо того, как Кааба была построена, Бог повелел своему пророку Аврааму, мир ему, оставить свою жену Хаджар и его ребенка, Исмаил, в долину клиники, в которой никто не жил. Авраам выполнил божественное командование, оставил их там, а затем поднял свои руки с молитвацией:\r\n\r\n«Наш Господь, я жил от моего потомства, с местом, которое не перегружено вашим запретным домом ...»\r\n(Ибрагим: 37)\r\n\r\nВода выбежала, и Хаджар закричал, и она бежала между Аль -сафу и Аль -Марух, искав каплю воды, пока он не взорвался из -под подножия исмаила Замзама воды, так что пульс начался на этой земле, и племени начинают оседать, сверху племя джарры.\r\n\r\nПрошло годы, Исмаил вырос, и Ибрагим вернулся, чтобы навестить свою семью. Затем Бог открыл ему, чтобы построить дом, в котором ему поклоняются один. Ибрагим и его сын Исмаил встали и готовы поднять правила дома.\r\n\r\nИмея руку, полную веры, Авраам, мир ему, начал ловить камни, и Исмаил делает его, и они повторяют просьбу, которое увековечило стихи:\r\n\r\nИ когда Авраам воспитывает Аль -Кавасад из дома и Иссахила: наш Господь принимает нас, потому что вы наиболее знающие. \"\r\n(Аль -Бакара: 127)\r\n\r\nЧем выше строительство, тем больше Авраама нуждалось то, что помогает ему достичь самого высокого, поэтому на нем появился камень, поэтому он оставил эффект своих ног. Этот камень все еще сохраняется рядом с Каабой, известной как макам Ибрагима.\r\n\r\nИ когда строительство Каабы было завершено, Ибрагим назвал своего Господа великим приглашением:\r\n\r\n«Господи, сделай эту безопасную страну и отдай его людей из фруктов ...»\r\n(Аль -Бакара: 126)\r\n\r\nС этого момента священный дом стал поцелуем для мусульман, и он очарован сердцами людей со всех частей земли.\r\n\r\nКааба, построенная Авраамом и его сыном, представляет собой не только строительство камней, но и символ абсолютного послушания, сдачи Богу и чистого поклонения."
      //     },
      //     {
      //       "id": 44,
      //       "swalef_id": 12,
      //       "locale": "zh",
      //       "title": "Kaaba的建造",
      //       "description": "上帝的先知亚伯拉罕（Abraham）的故事，是他在kaaba的建造中。",
      //       "content": "在贫瘠的土地上，没有着陆或水，在麦加山的山麓上，历史上最伟大的故事之一开始了……这是Kaaba建设的故事，Kaaba的故事是人们敬拜上帝的第一所房子。\r\n\r\n在Kaaba建造之前，上帝命令他的先知亚伯拉罕，和平，让他的妻子哈哈尔和他的孩子伊斯梅尔（Ismail）进入没有人居住的诊所的山谷。亚伯拉罕执行了神的​​命令，把他们留在那里，然后祈祷举起手：\r\n\r\n“我们的主，我从后代生活，一个没有被禁止的房子超负荷的地方……”\r\n（易卜拉欣：37）\r\n\r\n水用完了，哈哈尔大喊，她逃离了艾尔·萨法（Al -Safa）和艾尔·马鲁克（Al -Marukh），寻找一滴水，直到他从伊斯梅尔·扎姆扎姆（Ismail Zamzam of Water）的脚下爆炸，以便脉搏在地球上开始，部落开始定居在贾拉部落的顶部。\r\n\r\n多年过去了，伊斯梅尔长大了，易卜拉欣回去探望他的家人。然后，上帝敞开了他，建造了一个他一个人敬拜的房子。易卜拉欣和他的儿子伊斯梅尔起身，准备提出房屋的规则。\r\n\r\n亚伯拉罕拥有充满信心的手，愿他安息，开始抓住石头，伊斯梅尔（Ismail\r\n\r\n当亚伯拉罕从家里和伊萨克希尔（Issakhil）抚养艾尔·卡瓦萨德（Al -kavasad）时：我们的主接受我们，因为您是最知识的。 “\r\n（Al -Bakara：127）\r\n\r\n结构越高，亚伯拉罕需要的东西就越有助于他达到最高，因此出现了一块石头，因此他留下了腿的效果。这块石头仍然保存在Kaaba旁边，被称为易卜拉欣·马卡姆（Ibrahim Makam）。\r\n\r\n当建造Kaaba完成时，易卜拉欣称他的主是一个很好的邀请：\r\n\r\n“主，成为这个安全的国家，并将其从水果中送给人们……”\r\n（Al -Bakara：126）\r\n\r\n从那一刻起，这座神圣的房子成为了穆斯林的吻，它对地球各地的人们的心着迷。\r\n\r\n由亚伯拉罕（Abraham）和他的儿子建造的Kaaba不仅是石头的建造，而且是绝对服从，向上帝投降和纯粹敬拜的象征。"
      //     }
      //   ],
      //   "is_favorite": false,
      //   "is_saved": false
      // };
      console.log(this.storyDetails);

      // this.storyDetails['reviews'] = this.generateDummyReviews();
      if (isPlatformBrowser(this.platformId)) {
        this.updateMetaTags();
      }
      if (isPlatformServer(this.platformId)) {
        this.updateMetaTags();
      }
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoading = false;
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.storyDetails.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.storyDetails.title}` },
      { name: 'description', content: this.storyDetails.description },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stories/${this.storyDetails.slug}` },
      { property: 'og:title', content: `${this.storyDetails.title}` },
      { property: 'og:description', content: this.storyDetails.description },
    ]);
    this.metadataService.setSharePreviewImage(this.storyDetails.image);
  }
  private handleStoryDetailsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoading = false;
  }
  private generateDummyReviews(): any[] {
    return [
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
    ];
  }

  getFeaturedStories(): void {
    this.isLoadingFeaturedStories = true;
    this.storiesService?.getRecentStories({ page: this.page, per_page: this.perPage, top_featured: true })?.subscribe(
      (res: any) => this.handleFeaturedStoriesResponse(res),
      (err: any) => this.handleFeaturedStoriesError(err)
    );
  }
  private handleFeaturedStoriesResponse(res: any): void {
    if (res?.code === 200) {
      this.processFeaturedStoriesData(res);
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingFeaturedStories = false;
  }
  private processFeaturedStoriesData(res: any): void {
    if (res?.data?.items) {
      res.data.items.forEach((element: any) => {
        element['rate'] = element?.rate ? Math.round(element?.rate) : 0;
      });
      this.featuredStories = res.data.items;
    }
  }
  private handleFeaturedStoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingFeaturedStories = false;
  }

  leaveReview(): void {
    const ref = this.dialogService.open(RateComponent, {
      header: this.publicService?.translateTextFromJson('general.rate'),
      width: '45%',
      baseZIndex: 10000,
      data: {
        type: 'swalefs',
        parentId: this.storyId
      },
      styleClass: 'rate'
    });
  }
  share(): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl
      },
      styleClass: 'rate'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    })
  }
  handleViewAll(): void {

  }
  protected copyAudioLink(): void {
    const link = this.storyDetails?.audioStoryLink;
    if (link) {
      navigator.clipboard.writeText(link).then(() => {
        this.copied = true;
        setTimeout(() => {
          this.copied = false;
        }, 2000);
      });
    }
  }
  protected getCategoryBackground(index: number): string {
    const backgrounds = [
      '#CAD6FF',
      '#EDD3FF',
      '#D3F5FF',
      '#FFE2D3',
      '#D3FFE5',
      '#FFF7D3',
      '#F0D3FF',
    ];

    return backgrounds[index % backgrounds.length];
  }
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
