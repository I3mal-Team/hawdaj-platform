import { PublicService } from 'src/app/modules/shared/services/public.service';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TextTruncateDirective } from 'src/app/modules/shared/directives/text-truncate.directive';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-tour-guide-card',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, RouterModule, TextTruncateDirective, StripHtmlPipe],
  templateUrl: './tour-guide-card.component.html',
  styleUrls: ['./tour-guide-card.component.scss']
})
export class TourGuideCardComponent {

  @Input() item: any;
  @Input() truncateText: boolean = false;

  currentLanguage: string = '';

  constructor(
    private publicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit(): void {
    this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
      if (res === true) {
        this.currentLanguage = this.publicService.getCurrentLanguage();
      }
    });
  }

  handleImageError(event: Event, gender: string): void {
    if (isPlatformBrowser(this.platformId)) {
      const imgElement = event.target as HTMLImageElement;

      const defaultImage =
        gender === 'male'
          ? 'assets/images-v2/pages/tour-guides-list/cards/man.png'
          : 'assets/images-v2/pages/tour-guides-list/cards/woman.png';

      if (imgElement) {
        this.checkImageValidity(imgElement.src).then((isValid) => {
          if (!isValid) {
            imgElement.src = defaultImage;
          }
        });
      }
    }
  }


  private checkImageValidity(imageUrl: string): Promise<boolean> {
    return new Promise((resolve) => {
      const img = new Image();
      img.src = imageUrl;

      img.onload = () => resolve(true);
      img.onerror = () => resolve(false);
    });
  }
}
