import { environment } from '../../../../../environments/environment';
import { Category } from '../../../../interfaces/create-trip';
import { Component, EventEmitter, Inject, Input, Output, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';

@Component({
  selector: 'category-card',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './category-card.component.html',
  styleUrls: ['./category-card.component.scss']
})
export class CategoryCardComponent {


  constructor(@Inject(PLATFORM_ID) private platformId: Object) {

  }
  @Input() item: Category;
  @Output() cardEmit = new EventEmitter();

  selectCategory(): void {
    this.item.isChecked = !this.item?.isChecked;
    this.cardEmit.emit();
  }
}
