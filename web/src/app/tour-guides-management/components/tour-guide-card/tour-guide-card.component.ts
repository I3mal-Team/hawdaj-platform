import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { environment } from 'src/environments/environment';
import { RouterModule } from '@angular/router';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-tour-guide-card',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage, RouterModule, StripHtmlPipe],
  templateUrl: './tour-guide-card.component.html',
  styleUrls: ['./tour-guide-card.component.scss']
})
export class TourGuideCardComponent {

  @Input() item: any;
  onImageError(event: Event) {
    const imgElement = event.target as HTMLImageElement;
    if (this.item?.gender === 'male') {
      imgElement.src = 'assets/images-v2/pages/tour-guides-list/cards/man.png';
    }
    else if (this.item?.gender !== 'male') {
      imgElement.src = 'assets/images-v2/pages/tour-guides-list/cards/woman.png';
    }

  }
}
