import { Component, Inject, Input, PLATFORM_ID, ViewChild } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { CarouselModule } from 'primeng/carousel';
import { DialogService } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-home-restaurants',
  standalone: true,
  imports: [CommonModule, CarouselModule, RouterModule, TranslateModule, NgOptimizedImage],
  templateUrl: './home-restaurants.component.html',
  styleUrls: ['./home-restaurants.component.scss']
})
export class HomeRestaurantsComponent {
  @Input() restaurants: any = [];

  responsiveOptions: any;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogService: DialogService,
    private publicService: PublicService
  ) {
    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 2.5,  // 2.5 items visible at screen width 1024px or above
        numScroll: 1
      },
      {
        breakpoint: '768px',
        numVisible: 2,  // 2 items visible at screen width 768px or above
        numScroll: 1
      },
      {
        breakpoint: '560px',
        numVisible: 1.5,  // 1.5 items visible at screen width 560px or above
        numScroll: 1
      },
      {
        breakpoint: '450px',
        numVisible: 1,  // 1 item visible at screen width 450px or above
        numScroll: 1
      }
    ];
  }

  openMap(el: any): void {
    let data: any = [];
    data.push({
      lat: el?.lat,
      lng: el?.long,
      name: el?.description,
      image: el?.image,
      address_name: el?.address_name,
      review: 8,
      rate: 2,
      place_icon: el?.place_icon
    });
    const ref = this.dialogService.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: data,
      dismissableMask: true,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
    ref.onClose.subscribe(() => {
      this.publicService.toggleBodyScroll(true);
    });
  }
}
