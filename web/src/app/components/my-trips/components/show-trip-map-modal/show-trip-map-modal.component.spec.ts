import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowTripMapModalComponent } from './show-trip-map-modal.component';

describe('ShowTripMapModalComponent', () => {
  let component: ShowTripMapModalComponent;
  let fixture: ComponentFixture<ShowTripMapModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ShowTripMapModalComponent]
    });
    fixture = TestBed.createComponent(ShowTripMapModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
