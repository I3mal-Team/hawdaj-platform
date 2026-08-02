import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';

@Component({
  selector: 'app-details-banner',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage, LazyLoadSectionDirective],
  templateUrl: './details-banner.component.html',
  styleUrls: ['./details-banner.component.scss']
})
export class DetailsBannerComponent {
  @Input() item: any;

}
