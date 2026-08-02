import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { GalleriaModule } from 'primeng/galleria';
import { Router, RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';
import { AlertsService } from 'src/app/services/alerts.service';
import { PlacesService } from 'src/app/services/places.service';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { environment } from 'src/environments/environment';

@Component({
  standalone: true,
  imports: [
    TranslateModule,
    GalleriaModule,
    RouterModule,
    CommonModule,
    NgOptimizedImage,
    // Components
    SkeletonComponent,

    // Directives
    LazyLoadDirective
  ],
  selector: 'app-destination-slider',
  templateUrl: './destination-slider.component.html',
  styleUrls: ['./destination-slider.component.scss']
})
export class DestinationSliderComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  @Input() items: any;

  destinationSlider: any = [];
  slideData: any;
  customOptions: any;
  destinationSliderOptions = [
    {
      center: true,
      breakpoint: '1240px',
      numVisible: 4,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '991px',
      numVisible: 4,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '767px',
      numVisible: 4,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '620px',
      numVisible: 3,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '420px',
      numVisible: 2,
      numScroll: 1
    }
  ];
  isLoadingDestination: boolean = false;
  page: any = 1;
  perPage: any = 14;
  keyword: any = null;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private placesService: PlacesService,
    private alertsService: AlertsService,
    private publicService: PublicService,
    private router: Router
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.customOptions = {
      loop: true,
      infinite: true,
      mouseDrag: false,
      touchDrag: false,
      pullDrag: false,
      startPosition: 2,
      dots: false,
      navSpeed: 700,
      autoplay: true,
      merge: true,
      rtl: this.currentLanguage == 'ar' ? true : false,
      stagePadding: 150,
      navText: ['<span class="pi pi-angle-left scaleX-rtl fs-5"></span>', '<span class="pi pi-angle-right scaleX-rtl fs-5"></span>'],
      responsive: {
        0: {
          items: 1
        },
        400: {
          items: 2
        },
        740: {
          items: 3
        },
        940: {
          items: 3,
        },
        991: {
          items: 4,
        },
        1024: {
          items: 4
        }
      },
      nav: true
    }
    if (isPlatformBrowser(this.platformId)) {
      this.getTopDestinations();
    }
  }

  getTopDestinations(): void {
    this.isLoadingDestination = true;
    if (isPlatformBrowser(this.platformId)) {
      this.placesService?.getPlaces(this.page, this.perPage, null, null, null, null, null, null, null, true)?.subscribe(
        (res: any) => this.handleDestinationResponse(res),
        (err: any) => this.handleDestinationError(err)
      );
    }
  }
  handleDestinationResponse(res: any): void {
    if (res?.code === 200) {
      this.processDestinationItems(res.data?.items);
    } else {
      this.handleDestinationError(res?.message);
    }
  }
  processDestinationItems(items: any[]): void {
    if (items && items?.length > 0) {
      items.forEach((item: any) => {
        this.processAddress(item);
        this.processAddressName(item);
      });
      this.destinationSlider = items;
      this.slideData = this.destinationSlider[0];
    }
    this.isLoadingDestination = false;
  }
  processAddress(item: any): void {
    if (item?.lat && item?.long && item?.address_type === 'map') {
      item.address = this.publicService.createGoogleMapsLink(item.lat, item.long);
    }
  }
  processAddressName(item: any): void {
    if (item?.region?.name && item?.city?.name) {
      item.address_name = `${item.region.name}, ${item.city.name}`;
    } else {
      item.address_name = item?.region?.name || item?.city?.name || '';
    }
  }
  handleDestinationError(err: any): void {
    const errorMessage = err ? err : 'An error occurred while fetching destinations.';
    this.alertsService?.openToast('error', errorMessage);
    this.isLoadingDestination = false;
  }

  change(event: any): void {
    this.destinationSlider?.forEach((item: any) => {
      if (item?.id == +event?.slides[0]?.id) {
        this.slideData = item;
      }
    });
  }

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug])
    }
  }

  ngOnDestroy(): void {
    this.unsubscribe.forEach((sb) => sb.unsubscribe());
  }
}
