import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaveTripV2Component } from './save-trip-v2.component';

describe('SaveTripV2Component', () => {
  let component: SaveTripV2Component;
  let fixture: ComponentFixture<SaveTripV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SaveTripV2Component]
    });
    fixture = TestBed.createComponent(SaveTripV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
