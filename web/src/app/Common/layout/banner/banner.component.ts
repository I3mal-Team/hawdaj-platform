import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';

@Component({
  selector: 'app-banner',
  standalone: true,
  imports: [CommonModule, TranslateModule, LazyLoadImageDirective, LazyLoadSectionDirective, NgOptimizedImage],
  templateUrl: './banner.component.html',
  styleUrls: ['./banner.component.scss']
})
export class BannerComponent {
  @Input() title: string = '';
  @Input() largeImage: string = '';
  @Input() smallImage: string = '';
  @Input() description1: string = '';
  @Input() description2: string = '';
}
