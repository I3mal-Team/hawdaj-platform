import {
  ChangeDetectionStrategy,
  Component,
  EventEmitter,
  Output,
  signal,
  computed
} from '@angular/core';
import { CommonModule } from '@angular/common';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators
} from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { DynamicDialogRef, DynamicDialogConfig } from 'primeng/dynamicdialog';
import { DropdownModule } from 'primeng/dropdown';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon/svg-icon.component';
import { SocialMediaItem, SocialMediaPlatform } from '../../property-item.model';
import {
  urlValidator,
  phoneOrUrlValidator
} from '../../property-form.validators';

type SocialMediaFormControls = {
  platform: FormControl<SocialMediaPlatform | null>;
  link: FormControl<string>;
};

@Component({
  selector: 'app-social-media-popup',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    TranslateModule,
    DropdownModule,
    SvgIconComponent
  ],
  templateUrl: './social-media-popup.component.html',
  styleUrls: ['./social-media-popup.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class SocialMediaPopupComponent {
  readonly socialMediaForm = new FormGroup<SocialMediaFormControls>({
    platform: new FormControl<SocialMediaPlatform | null>(null, {
      validators: [Validators.required]
    }),
    link: new FormControl<string>('', {
      nonNullable: true,
      validators: [Validators.required]
    })
  });

  readonly selectedPlatform = computed(() => this.controls.platform.value);
  readonly isWhatsApp = computed(() => this.selectedPlatform()?.id === 'whatsapp');

  readonly isSubmitting = signal(false);

  @Output() save = new EventEmitter<SocialMediaItem>();

  readonly data: {
    platforms: SocialMediaPlatform[];
    item?: SocialMediaItem;
  };

  constructor(
    private ref: DynamicDialogRef,
    private config: DynamicDialogConfig
  ) {
    this.data = this.config.data as {
      platforms: SocialMediaPlatform[];
      item?: SocialMediaItem;
    };

    if (this.data.item) {
      this.socialMediaForm.patchValue({
        platform: this.data.item.platform,
        link: this.data.item.link
      });
    }

    // Update link validator when platform changes
    this.controls.platform.valueChanges.subscribe(() => {
      this.updateLinkValidator();
    });

    // Set initial validator
    this.updateLinkValidator();
  }

  private updateLinkValidator(): void {
    const isWhatsApp = this.isWhatsApp();
    const linkControl = this.controls.link;

    if (isWhatsApp) {
      linkControl.setValidators([Validators.required, phoneOrUrlValidator()]);
    } else {
      linkControl.setValidators([Validators.required, urlValidator()]);
    }

    linkControl.updateValueAndValidity({ emitEvent: false });
  }

  get controls(): SocialMediaFormControls {
    return this.socialMediaForm.controls;
  }

  onSubmit(): void {
    if (this.socialMediaForm.invalid) {
      this.socialMediaForm.markAllAsTouched();
      return;
    }

    const formValue = this.socialMediaForm.getRawValue();
    if (!formValue.platform) {
      return;
    }

    this.isSubmitting.set(true);

    const socialMediaItem: SocialMediaItem = {
      id: this.data.item?.id ?? crypto.randomUUID(),
      platform: formValue.platform,
      link: formValue.link
    };

    setTimeout(() => {
      this.save.emit(socialMediaItem);
      this.ref.close(socialMediaItem);
      this.isSubmitting.set(false);
    }, 300);
  }

  onCancel(): void {
    this.ref.close();
  }

  shouldShowError(controlName: keyof SocialMediaFormControls): boolean {
    const control = this.controls[controlName];
    return !!control && control.invalid && (control.dirty || control.touched);
  }

  getErrorMessage(controlName: keyof SocialMediaFormControls): string {
    const control = this.controls[controlName];
    if (!control || !control.errors) {
      return '';
    }

    if (control.errors['required']) {
      return 'properties.socialMedia.errors.required';
    }

    if (control.errors['invalidUrl']) {
      return 'properties.socialMedia.errors.invalidUrl';
    }

    if (control.errors['invalidPhoneOrUrl']) {
      return 'properties.errors.invalidPhoneOrUrl';
    }

    return '';
  }
}

