import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { IApplication } from './interface/application-card.model';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-application-card-v2',
  standalone: true,
  imports: [CommonModule, NgOptimizedImage, TranslateModule, StripHtmlPipe],
  templateUrl: './application-card-v2.component.html',
  styleUrls: ['./application-card-v2.component.scss']
})
export class ApplicationCardV2Component {
  @Input() item!: IApplication;
  @Input() categoryIds!: any;
}
