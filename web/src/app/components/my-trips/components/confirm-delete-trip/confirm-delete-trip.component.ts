import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { InputTextModule } from 'primeng/inputtext';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';

@Component({
  standalone:true,
  imports:[
    CommonModule,
    TranslateModule,
    RouterModule,
  ],
  selector: 'app-confirm-delete-trip',
  templateUrl: './confirm-delete-trip.component.html',
  styleUrls: ['./confirm-delete-trip.component.scss']
})
export class ConfirmDeleteTripComponent implements OnInit {

  constructor(
    public config: DynamicDialogConfig,
    public ref: DynamicDialogRef
  ) { }

  ngOnInit(): void {
  }

  confirm() {
    this.ref?.close({ isConfirmed: true });
  }
  close() {
    this.ref?.close();
  }
}
