import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StartTripStepsComponent } from './start-trip-steps.component';

describe('StartTripStepsComponent', () => {
  let component: StartTripStepsComponent;
  let fixture: ComponentFixture<StartTripStepsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StartTripStepsComponent]
    });
    fixture = TestBed.createComponent(StartTripStepsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
