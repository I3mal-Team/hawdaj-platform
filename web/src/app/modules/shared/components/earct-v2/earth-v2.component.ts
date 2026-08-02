import { Component, Input, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { SkeletonComponent } from '../skeleton/skeleton.component';
import { TranslateModule } from '@ngx-translate/core';
import { isPlatformBrowser } from '@angular/common';

@Component({
  selector: 'app-earth-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, SkeletonComponent],
  templateUrl: './earth-v2.component.html',
  styleUrls: ['./earth-v2.component.scss']
})
export class EarthV2Component {
  countries = ['Germany', 'Saudi Arabia', 'Egypt', 'United States', 'Russia', 'China', 'India', 'United Kingdom'];
  isLoading: boolean = false;
  specificCountries: any = [];
  isImageLoaded = false;
  @Input() items: any;

  constructor(@Inject(PLATFORM_ID) private platformId: object) {}

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.countries.forEach(country => {
        this.isLoading = true;
        this.specificCountries = this.countries.map(country => {
          return this.items.find((item: any) => item.name === country);
        });
      });
    }
  }

  onImageLoad() {
    this.isImageLoaded = true;
  }
}
