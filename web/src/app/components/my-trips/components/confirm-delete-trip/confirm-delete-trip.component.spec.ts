import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConfirmDeleteTripComponent } from './confirm-delete-trip.component';

describe('ConfirmDeleteTripComponent', () => {
  let component: ConfirmDeleteTripComponent;
  let fixture: ComponentFixture<ConfirmDeleteTripComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ConfirmDeleteTripComponent]
    });
    fixture = TestBed.createComponent(ConfirmDeleteTripComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
