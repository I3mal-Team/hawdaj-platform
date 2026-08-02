import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VerificationOtpPopupComponent } from './verification-otp-popup.component';

describe('VerificationOtpPopupComponent', () => {
  let component: VerificationOtpPopupComponent;
  let fixture: ComponentFixture<VerificationOtpPopupComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [VerificationOtpPopupComponent]
    });
    fixture = TestBed.createComponent(VerificationOtpPopupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
