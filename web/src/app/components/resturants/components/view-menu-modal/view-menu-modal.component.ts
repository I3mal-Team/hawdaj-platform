import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  standalone: true,
  imports: [
    TranslateModule,
    CommonModule
  ],
  selector: 'app-view-menu-modal',
  templateUrl: './view-menu-modal.component.html',
  styleUrls: ['./view-menu-modal.component.scss']
})
export class ViewMenuModalComponent {
  data: any;
  constructor(
    private ref: DynamicDialogRef,
    private config: DynamicDialogConfig
  ) {
    this.data = config?.data;
  }
  close(): void {
    this.ref?.close();
  }
}
