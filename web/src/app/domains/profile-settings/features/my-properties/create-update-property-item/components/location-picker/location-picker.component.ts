import { Component, ViewChild, Inject, PLATFORM_ID, OnInit } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { GoogleMapsModule, GoogleMap, MapMarker } from '@angular/google-maps';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { LocationData } from '../../property-item.model';

@Component({
  selector: 'app-location-picker',
  standalone: true,
  imports: [
    CommonModule,
    GoogleMapsModule,
    FormsModule,
    TranslateModule,
    InputTextModule,
    ButtonModule
  ],
  templateUrl: './location-picker.component.html',
  styleUrls: ['./location-picker.component.scss']
})
export class LocationPickerComponent implements OnInit {
  @ViewChild(GoogleMap, { static: false }) map!: GoogleMap;

  center: google.maps.LatLngLiteral = { lat: 24.774265, lng: 46.738586 }; // Riyadh, Saudi Arabia
  zoom = 10;
  markerPosition: google.maps.LatLngLiteral | null = null;
  searchValue: string = '';
  selectedLocation: LocationData | null = null;
  geocoder: google.maps.Geocoder | null = null;
  currentLanguage: string = 'ar';

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef,
    private publicService: PublicService
  ) {}

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      
      // Initialize geocoder
      if (typeof google !== 'undefined' && google.maps) {
        this.geocoder = new google.maps.Geocoder();
      }

      // If initial location is provided
      if (this.config?.data?.location) {
        const loc = this.config.data.location;
        this.center = { lat: loc.lat, lng: loc.lng };
        this.markerPosition = { lat: loc.lat, lng: loc.lng };
        this.searchValue = loc.address || loc.name || '';
      }
    }
  }

  onMapClick(event: google.maps.MapMouseEvent): void {
    if (event.latLng) {
      const lat = event.latLng.lat();
      const lng = event.latLng.lng();
      this.markerPosition = { lat, lng };
      this.getAddressFromCoordinates(lat, lng);
    }
  }

  getAddressFromCoordinates(lat: number, lng: number): void {
    if (!this.geocoder) return;

    this.geocoder.geocode(
      { location: { lat, lng } },
      (results, status) => {
        if (status === 'OK' && results && results[0]) {
          this.searchValue = results[0].formatted_address;
        } else {
          this.searchValue = `${lat}, ${lng}`;
        }
      }
    );
  }

  searchLocation(): void {
    if (!this.searchValue.trim() || !this.geocoder) return;

    this.geocoder.geocode(
      { address: this.searchValue },
      (results, status) => {
        if (status === 'OK' && results && results[0]) {
          const location = results[0].geometry.location;
          const lat = location.lat();
          const lng = location.lng();
          this.center = { lat, lng };
          this.markerPosition = { lat, lng };
          this.searchValue = results[0].formatted_address;
        }
      }
    );
  }

  confirmSelection(): void {
    if (this.markerPosition) {
      this.selectedLocation = {
        lat: this.markerPosition.lat,
        lng: this.markerPosition.lng,
        address: this.searchValue,
        name: this.searchValue
      };

      console.log('Selected Location:', this.selectedLocation);
      this.ref.close(this.selectedLocation);
    }
  }

  cancel(): void {
    this.ref.close(null);
  }
}










