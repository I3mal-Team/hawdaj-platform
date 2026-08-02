import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { RatingModule } from 'primeng/rating';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { LazyLoadImageDirective } from '../../../../modules/shared/directives/lazy-load-image.directive';
import { EventsService } from '../../../../services/events.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { environment } from '../../../../../environments/environment';
import { DialogService } from 'primeng/dynamicdialog';
import { VideoModalComponent } from '../videos-slider/video-modal/video-modal.component';
import { ShowTripMapModalComponent } from '../../../my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { Subscription } from 'rxjs/internal/Subscription';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'home-events',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    CarouselModule,  // Import PrimeNG CarouselModule
    RatingModule,
    FormsModule,
    RouterModule,
    LazyLoadImageDirective,
    SkeletonComponent,
    NgOptimizedImage,
    StripHtmlPipe
  ],
  templateUrl: './home-events.component.html',
  styleUrls: ['./home-events.component.scss']
})
export class HomeEventsComponent {
  private subscriptions: Subscription[] = [];

  currentEvent: any;
  eventsActiveIndex: any = 0;
  isLoadingEvents: boolean = false;
  @Input() events: any = [];

  responsiveOptions: any[];

  constructor(
    private alertsService: AlertsService,
    private publicService: PublicService,
    private eventsService: EventsService,
    private dialogService: DialogService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 2, // Show 2.5 cards on screens wider than 1240px
        numScroll: 1
      },
      {
        breakpoint: '991px',
        numVisible: 1.5, // Show 1.5 cards on screens between 991px and 1240px
        numScroll: 1
      },
      {
        breakpoint: '767px',
        numVisible: 2.5, // Show 2.5 cards on screens between 767px and 991px
        numScroll: 1
      },
      {
        breakpoint: '480px',
        numVisible: 1, // Show 1 card on screens smaller than 480px
        numScroll: 1
      }
    ];
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.events?.length > 0) {
        this.setCurrentEvent(this.events[0]);
      }
    }
  }

  onEventChange(event: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.eventsActiveIndex = event.page;
      this.setCurrentEvent(this.events[event.page]);
    }
  }

  private setCurrentEvent(event: any): void {
    if (event) {
      event['markerPositions'] = [{
        lat: event?.lat,
        lng: event?.long,
        icon: {
          url: event?.place_icon ? `${event?.place_icon}` : 'assets/images/icons/location2.svg',
          size: event?.place_icon ? new google.maps.Size(30, 30) : new google.maps.Size(50, 50),
        },
        content: {
          id: event?.id,
          title: event?.title,
          location_name: event?.address_name,
          thumbil_image: `${event?.image}`,
          review: event?.review,
          rate: event?.rate ? event?.rate : 0
        }
      }];
      this.currentEvent = event;
    }
  }

  openVideo(item: any): void {
    if (isPlatformBrowser(this.platformId)) {
      const ref = this.dialogService.open(VideoModalComponent, {
        header: '',
        width: '90%',
        baseZIndex: 10000,
        data: {
          url_video: item?.video_url,
          image_video: item?.image,
        },
        styleClass: 'video-modal'
      });
      ref.onClose.subscribe((res: any) => {
        if (res) {
        }
      })
    }
  }

  openMap(event: any): void {
    let data: any = [];
    data?.push({
      lat: event?.lat,
      lng: event?.long,
      name: event?.description,
      image: event?.image,
      address_name: event?.address_name,
      review: 8,
      rate: 2,
      place_icon: event?.place_icon
    });
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: data,
      dismissableMask: true,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
