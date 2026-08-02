import { Component, EventEmitter, inject, Input, Output, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { out } from '@amcharts/amcharts5/.internal/core/util/Ease';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-offer',
  standalone: true,
  imports: [CommonModule, TranslateModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './offer.component.html',
  styleUrls: ['./offer.component.scss']
})
export class OfferComponent {
  currentLanguage: string;

  private publicService = inject(PublicService);
  private platformId = inject(PLATFORM_ID);


  @Input() title: string;
  @Input() imgSrc: string;
  @Output() offerClick = new EventEmitter<void>();

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  openDetails() {
    this.offerClick.emit();
  }

}
