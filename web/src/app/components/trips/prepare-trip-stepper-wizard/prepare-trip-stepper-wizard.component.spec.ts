import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PrepareTripStepperWizardComponent } from './prepare-trip-stepper-wizard.component';

describe('PrepareTripStepperWizardComponent', () => {
  let component: PrepareTripStepperWizardComponent;
  let fixture: ComponentFixture<PrepareTripStepperWizardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PrepareTripStepperWizardComponent]
    });
    fixture = TestBed.createComponent(PrepareTripStepperWizardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
