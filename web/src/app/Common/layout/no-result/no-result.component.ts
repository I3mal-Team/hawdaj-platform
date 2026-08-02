import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  selector: 'app-no-result',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './no-result.component.html',
  styleUrls: ['./no-result.component.scss']
})
export class NoResultComponent {
  @Input() isWhite: boolean;
  @Input() title: string = 'general.noData';
}
