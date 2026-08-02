import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaveTripModalComponent } from './save-trip-modal.component';

describe('SaveTripModalComponent', () => {
  let component: SaveTripModalComponent;
  let fixture: ComponentFixture<SaveTripModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [SaveTripModalComponent]
    });
    fixture = TestBed.createComponent(SaveTripModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
