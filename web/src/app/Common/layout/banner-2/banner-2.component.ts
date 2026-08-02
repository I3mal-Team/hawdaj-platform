import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  selector: 'app-banner-2',
  standalone: true,
  imports: [CommonModule, TranslateModule, LazyLoadImageDirective],
  templateUrl: './banner-2.component.html',
  styleUrls: ['./banner-2.component.scss']
})
export class Banner2Component {
  @Input() title: string;
  @Input() subtitle?: string;
  @Input() largeImage: string = '';
  @Input() smallImage: string = '';
}
