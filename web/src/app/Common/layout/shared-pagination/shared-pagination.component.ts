import { Component, EventEmitter, inject, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';
import { ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-shared-pagination',
  standalone: true,
  imports: [CommonModule, TranslateModule, ReactiveFormsModule],
  templateUrl: './shared-pagination.component.html',
  styleUrls: ['./shared-pagination.component.scss']
})
export class SharedPaginationComponent {
  currentLanguage: any;
  @Input() itemsPageNumber: number = 1;
  @Input() itemsListTotalCount: number = 0;
  @Input() itemsPerPageCount: number = 0;
  @Output() pageChange = new EventEmitter<number>();

  public publicService = inject(PublicService)

  ngOnInit() {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  changePage(direction: number) {
    this.pageChange.emit(direction);
  }

  getCeilValue() {
    return Math.ceil(this.itemsListTotalCount / this.itemsPerPageCount);
  }
}