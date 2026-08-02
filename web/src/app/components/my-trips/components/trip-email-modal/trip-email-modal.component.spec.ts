import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TripEmailModalComponent } from './trip-email-modal.component';

describe('TripEmailModalComponent', () => {
  let component: TripEmailModalComponent;
  let fixture: ComponentFixture<TripEmailModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [TripEmailModalComponent]
    });
    fixture = TestBed.createComponent(TripEmailModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
