import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PrepearTripStepperComponent } from './prepear-trip-stepper.component';

describe('PrepearTripStepperComponent', () => {
  let component: PrepearTripStepperComponent;
  let fixture: ComponentFixture<PrepearTripStepperComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PrepearTripStepperComponent]
    });
    fixture = TestBed.createComponent(PrepearTripStepperComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
