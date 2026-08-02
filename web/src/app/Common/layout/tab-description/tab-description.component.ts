import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

import { SafeHtmlPipe } from '../../pipes/safe-html.pipe';

@Component({
  selector: 'app-tab-description',
  standalone: true,
  imports: [CommonModule, TranslateModule, SafeHtmlPipe],
  templateUrl: './tab-description.component.html',
  styleUrls: ['./tab-description.component.scss']
})
export class TabDescriptionComponent {
  @Input() item: any;
}
