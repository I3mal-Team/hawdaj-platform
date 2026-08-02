import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  selector: 'app-language-card',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './language-card.component.html',
  styleUrls: ['./language-card.component.scss']
})
export class LanguageCardComponent {
  @Input() item: any;

}
