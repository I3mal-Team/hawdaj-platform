import { environment } from '../../../../../environments/environment';
import { AlertsService } from 'src/app/services/alerts.service';
import { VideoModalComponent } from './video-modal/video-modal.component';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { EventsService } from '../../../../services/events.service';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { DialogService } from 'primeng/dynamicdialog';
import { isPlatformBrowser } from '@angular/common';
import { Subscription } from 'rxjs';
import { PublicService } from '../../../../modules/shared/services/public.service';

@Component({
  selector: 'app-videos-slider',
  templateUrl: './videos-slider.component.html',
  styleUrls: ['./videos-slider.component.scss'],
  providers: [DialogService]
})
export class VideosSliderComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  responsiveOptions: any = [
    {
      breakpoint: '1199px',
      numVisible: 1,
      numScroll: 1
    },
    {
      breakpoint: '991px',
      numVisible: 1,
      numScroll: 1
    },
    {
      breakpoint: '767px',
      numVisible: 1,
      numScroll: 1
    }
  ];
  events: any = [];
  isLoadingEvents: boolean = false;
  page: any = 1;
  perPage: any = 7;
  keyword: any = null;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    private eventsService: EventsService,
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.getEvents();
  }

  getEvents(): void {
    this.isLoadingEvents = true;
    this.eventsService?.getAllEvents(this.page, this.perPage, null, null, null, true)?.subscribe(
      (res: any) => this.handleEventsResponse(res),
      (err: any) => this.handleEventsError(err)
    );
  }
  handleEventsResponse(res: any): void {
    if (res?.code === 200) {
      this.processEvents(res.data?.items);
    } else {
      this.handleEventsError(res?.message);
    }
  }
  processEvents(events: any[]): void {
    if (events && events?.length > 0) {
      events.forEach((event: any) => {
        this.processEventAddressName(event);
      });
      this.events = events;
    }
    this.isLoadingEvents = false;
  }
  processEventAddressName(event: any): void {
    if (event?.region?.name && event?.city?.name) {
      event.address_name = `${event.region.name}, ${event.city.name}`;
    } else {
      event.address_name = event?.region?.name || event?.city?.name || '';
    }
  }
  handleEventsError(err: any): void {
    const errorMessage = err ? err.message : 'An error occurred while fetching events.';
    this.alertsService?.openToast('error', errorMessage);
    this.isLoadingEvents = false;
  }

  openVideo(item: any): void {
    const ref = this.dialogService.open(VideoModalComponent, {
      header: '',
      width: '90%',
      baseZIndex: 10000,
      data: {
        url_video: item?.video_url,
        image_video: this.item?.image,
      },
      styleClass: 'video-modal'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    })
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
