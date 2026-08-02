import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  selector: 'app-region-card',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './region-card.component.html',
  styleUrls: ['./region-card.component.scss']
})
export class RegionCardComponent {
  @Input() region: any;
  onImageError(event: Event) {
    const imgElement = event.target as HTMLImageElement;
    imgElement.src = 'assets/images-v2/pages/tour-guide-details/no-result/region-onerror.webp';
  }

}
