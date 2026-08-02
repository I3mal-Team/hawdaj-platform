import { ChangeDetectorRef, Component, EventEmitter, inject, Input, Output, PLATFORM_ID, QueryList, SimpleChanges, ViewChild, ViewChildren } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { GoogleMapsModule, MapInfoWindow, MapMarker } from '@angular/google-maps';
import { darkModeTheme } from 'src/app/components/home-page/components/map/map-options';
import { TabViewModule } from 'primeng/tabview';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { DialogService } from 'primeng/dynamicdialog';
import { ComingSoonModalComponent } from 'src/app/modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { environment } from 'src/environments/environment';
import { Router } from '@angular/router';
import { MapInformationComponent } from "../../component/map-information/map-information.component";
import { TabDescriptionComponent } from "../tab-description/tab-description.component";
import { ReviewsSliderListComponent } from "../../../shared/components/reviews-slider/reviews-slider-list/reviews-slider-list.component";
import { NoResultComponent } from "../no-result/no-result.component";
import { ChatComponent } from "./chat/chat.component";


@Component({
  selector: 'app-tabs2',
  standalone: true,
  imports: [CommonModule, TranslateModule, GoogleMapsModule, TabViewModule, SkeletonComponent, ReactiveFormsModule, MapInformationComponent, TabDescriptionComponent, ReviewsSliderListComponent, NoResultComponent, ChatComponent],
  templateUrl: './tabs2.component.html',
  styleUrls: ['./tabs2.component.scss']
})
export class Tabs2Component {
  @Input() placeDetails: any;
  @Input() tabsConfig: any;
  @Input() type: string;

  @Input() markerPositions: any;
  @Input() isLoadingReviews: boolean;
  @Input() activeIndex: any = 0;
  @Output() downloadMenu: EventEmitter<any> = new EventEmitter();
  @Output() openMenu: EventEmitter<any> = new EventEmitter();
  @Output() locationTab = new EventEmitter<void>();


  // Start Map Configs
  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;

  center: google.maps.LatLngLiteral = { lat: 24.774265, lng: 46.738586 }; // Coordinates of Riyadh, Saudi Arabia
  zoom: any = 5;
  darkMode: any = darkModeTheme;
  responsiveOptions: any;
  currentLanguage!: string;

  //rating
  ratingsPerPage = 3;
  currentRatingPage = 0;

  private platformId = inject(PLATFORM_ID);
  public publicService = inject(PublicService)
  private dialogService = inject(DialogService);


  private fb = inject(FormBuilder);
  public router = inject(Router)
  //chat

  chat: any = [];
  isLoadingChat: boolean = false;
  selectedFile: any = '';
  chatForm = this.fb.group({
    message: ['', { validators: [Validators.required], updateOn: 'change' }],
  });
  fullUrl: any = null;
  displayChat: boolean = false;


  constructor(private cdr: ChangeDetectorRef) {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.setRatingsPerPage(window.innerWidth);
    }
  }
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['isLoadingReviews'] && changes['isLoadingReviews'].currentValue === true) {
      this.activeIndex = 0;
    }
  }
  markerIcon(markerPosition: any) {
    return {
      url: markerPosition?.content?.thumbil_image,
      scaledSize: new google.maps.Size(50, 50)
    };
  }

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    this.center = {
      lat: this.placeDetails?.lat,
      lng: this.placeDetails?.long,
    };
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
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.selecedMarker = markerPosition?.content;
      this.infoWindow.open(marker);
    }
  }
  locationTabClick(index: number): void {
    this.locationTab.emit();
    if (this.activeIndex === index) {
      return;
    } else {
      this.activeIndex = index;
    }
  }
  closeAllInfoWindows(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.infoWindow?.close();
      this.center = {
        lat: this.placeDetails?.lat,
        lng: this.placeDetails?.long,
      };
    }
  }
  openMenuClick(): void {
    this.openMenu.emit();
  }
  downloadMenuFile(): void {
    this.downloadMenu.emit();
  }
  goToLocation(): void {
    if (isPlatformBrowser(this.platformId)) {
      window.open(this.placeDetails.address, '_blank');
    }
  }
  openComingSoon(): void {
    const ref = this?.dialogService?.open(ComingSoonModalComponent, {
      width: '35%',
      styleClass: '',
      header: '',
      dismissableMask: true,
    });
  }

  getDisplayedRatings() {
    const startIndex = this.currentRatingPage * this.ratingsPerPage;
    const endIndex = startIndex + this.ratingsPerPage;
    return this.placeDetails.ratings.slice(startIndex, endIndex);
  }

  prevRatingPage() {
    if (this.currentRatingPage > 0) {
      this.currentRatingPage--;
      if (isPlatformBrowser(this.platformId)) {
        if (this.ratingsPerPage === 3) {
          window.scrollTo({ top: 400, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 900, behavior: 'smooth' });
        }
      }
    }
  }

  nextRatingPage() {
    const totalPages = Math.ceil(this.placeDetails.ratings.length / this.ratingsPerPage);
    if (this.currentRatingPage < totalPages - 1) {
      this.currentRatingPage++;
      if (isPlatformBrowser(this.platformId)) {
        if (this.ratingsPerPage === 3) {
          window.scrollTo({ top: 400, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 900, behavior: 'smooth' });
        }
      }
    }
  }
  setRatingsPerPage(windowWidth: number) {
    this.ratingsPerPage = windowWidth < 767 ? 1 : 3;
  }
  onFileSelected(event: any): void {
    this.selectedFile = event.target.files[0] ?? null;
    if (this.selectedFile.size <= 5000) {
      this.chatForm.patchValue({
        message: this.selectedFile.name,
      });
      // this.chatForm.setValue()
    }
  }
  addToChat(e: any, message: any): void {
    this.chat.push({ text: message?.value, type: 'two' }), (message.value = '');
    setTimeout(() => {
      this.chat.push({ text: 'Thank You', type: 'one' });
    }, 5000);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(e.yPosition);
    }
  }
}
