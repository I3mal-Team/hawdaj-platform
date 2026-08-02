import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';

@Component({
  selector: 'app-bread-crumb',
  standalone: true,
  imports: [CommonModule, TranslateModule, LazyLoadSectionDirective],
  templateUrl: './bread-crumb.component.html',
  styleUrls: ['./bread-crumb.component.scss']
})
export class BreadCrumbComponent {
  @Input() locations: string[];
  @Input() isLoadingPlaceDetails: boolean;

}
