import { Component, EventEmitter, Inject, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { RouterModule } from '@angular/router';
import { out } from '@amcharts/amcharts5/.internal/core/util/Ease';


@Component({
  selector: 'app-confirm-logout',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule],
  templateUrl: './confirm-logout.component.html',
  styleUrls: ['./confirm-logout.component.scss']
})
export class ConfirmLogoutComponent {
  @Output() Confirm = new EventEmitter<void>();

  constructor(
    public config: DynamicDialogConfig,
    public ref: DynamicDialogRef
  ) { }

  confirm() {
    if (this.config.data?.onConfirm) {
      this.config.data.onConfirm();
    }
    this.ref?.close();
  }
  close() {
    this.ref?.close();
  }
}
