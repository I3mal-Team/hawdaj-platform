import { Component, ViewChild, ViewChildren, QueryList, ElementRef, Inject, PLATFORM_ID } from '@angular/core';
import { environment } from '../../../../../environments/environment';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { darkModeTheme } from '../../../home-page/components/map/map-options';
import { GoogleMapsModule, MapInfoWindow, MapMarker } from '@angular/google-maps';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { InputTextModule } from 'primeng/inputtext';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { MapInformationComponent } from "../../../../Common/component/map-information/map-information.component";

@Component({
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    GoogleMapsModule,
    InputTextModule,
    RouterModule,
    SkeletonComponent,
    MapInformationComponent
  ],
  selector: 'app-show-trip-map-modal',
  templateUrl: './show-trip-map-modal.component.html',
  styleUrls: ['./show-trip-map-modal.component.scss']
})
export class ShowTripMapModalComponent {
  private unsubscribe: Subscription[] = [];

  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;
  @ViewChild('searchMapInput', { static: false }) searchInputRef!: ElementRef;

  // center: google.maps.LatLngLiteral = { lat: 24.774265, lng: 46.738586 };// Coordinates of Riyadh, Saudi Arabia
  center!: google.maps.LatLngLiteral;// Coordinates of Riyadh, Saudi Arabia
  zoom: any = 5;
  darkMode: any = darkModeTheme;

  markerPositions: any = [];
  searchValue: any = null;
  currentLanguage: string;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef,
    private router: Router
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    let locations: any = this.config?.data;
    locations?.forEach((item: any) => {
      this.markerPositions?.push(
        {
          lat: item?.lat,
          lng: item?.lng,
          icon: {
            // url: item?.place_icon ? item?.place_icon : 'assets/images/icons/location2.svg',
            // size: item?.place_icon ? new google.maps.Size(50, 50) : new google.maps.Size(50, 50),
            url: item?.image ? item?.image : 'assets/images/not-found/no-img.svg',
            scaledSize: new google.maps.Size(30, 30),
          },
          content: {
            id: item?.id,
            slug: item?.slug,
            title: item.name,
            location_name: item.address_name,
            address: item.address || '/places/details/' + item?.slug,
            review: item.review,
            rate: item.rate ? item.rate : 0,
            type: item.type,
            icon: item.place_icon,
            thumbil_image: item.image ? item?.image : 'assets/images/icons/location2.svg'
          }
        }
      )
    });

    let count = 0;
    this.markerPositions?.forEach((el: any, index: any) => {
      if (el?.lat != 0 && el?.lng != 0) {
        this.center = { lat: this.markerPositions[index]?.lat, lng: this.markerPositions[index]?.lng };
      } else {
        count++;
      }
    });
    if (count == this.markerPositions?.length) {
      this.center = { lat: 24.774265, lng: 46.738586 };
    }
  }

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    // let newMarker: any = event.latLng.toJSON();
    // console.log(newMarker);
    // newMarker['content'] = { title: 'New Place', location_name: 'Location Name', thumbil_image: 'assets/images/icons/location.svg' };
    // this.markerPositions.push(newMarker);
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.markerPositions?.length > 1) {
        this.selecedMarker = markerPosition?.content;
        this.infoWindow.open(marker);
      } else {
        window.open(this.markerPositions[0]?.content?.address, '_blank');
      }
    }
  }
  closeAllInfoWindows(): void {
    this.infoWindow?.close();
  }
  onSearchMap(event: any): void {
    // console.log(event.target.value);
  }
  clearSearch(event?: any): void {
    this.searchValue = null;
    this.center = { lat: 24.774265, lng: 46.738586 };
    this.zoom = 5;
  }

  close(): void {
    this.ref?.close();
    this.clearSearch();
    this.closeAllInfoWindows();
    this.center = { lat: 24.774265, lng: 46.738586 };
    this.zoom = 5;
  }
  goToDetails(selecedMarker: any): void {
    if (selecedMarker?.slug) {
      this.router.navigate(['/places/details/', selecedMarker?.slug]);
      this.ref?.close();
    }

    // switch (selecedMarker?.type) {
    //   case 'place':
    //     break;
    //   case 'event':
    //     this.router.navigate(['/events/event-details/', selecedMarker?.slug]);
    //     break;
    //   case 'store':
    //     this.router.navigate(['/stores/', selecedMarker?.slug]);
    //     break;
    //   case 'zad':
    //     this.router.navigate(['/restaurants/', selecedMarker?.slug]);
    //     break;
    //   default:
    //     break;
    // }
  }
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
