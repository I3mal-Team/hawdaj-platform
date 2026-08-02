import { Component, EventEmitter, inject, Input, Output } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';

@Component({
  selector: 'app-rate-item',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, TranslateModule],
  templateUrl: './rate-item.component.html',
  styleUrls: ['./rate-item.component.scss']
})
export class RateItemComponent {
  @Input() currentLoginInformation: any;
  @Input() itemId: number | null = null;
  @Input() title: string = 'places.yourOpinion';
  @Input() loading: boolean = false;
  @Input() type: string;
  @Output() feedbackSubmitted = new EventEmitter<any>();

  private fb = inject(FormBuilder);
  public publicService = inject(PublicService)
  currentLanguage: string;

  form = this.fb.group({
    status: [null, [Validators.required]],
    name: ['', {
      validators: [Validators.required],
      updateOn: 'blur',
    },],
    email: ['', {
      validators: [Validators.required, Validators.pattern(patterns.email)],
      updateOn: 'blur',
    },
    ],
    massage: ['', {
      validators: [
        Validators.required,
        Validators.minLength(10),
      ],
      updateOn: 'blur',
    },]
  });
  get formControls(): any {
    return this.form?.controls;
  }
  ngOnInit() {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }
  sendFeedbackFromPlaces() {
    if (this?.form?.valid || (this.currentLoginInformation && this?.form?.get('status')?.valid && this?.form?.get('massage')?.valid)) {
      const feedbackData = {
        email: this.currentLoginInformation?.email || this.form?.value?.email,
        name: this.currentLoginInformation?.full_name || this.form?.value?.name,
        rate: this.form?.value?.status,
        rateText: this.form?.value?.massage,
        type: this.type,
        parent_id: this.itemId,
      };

      this.feedbackSubmitted.emit(feedbackData);
      this.cancel();
    } else {
      this.publicService.validateAllFormFields(this.form);
    }

  }

  cancel(): void {
    this.form?.reset();
  }
}
