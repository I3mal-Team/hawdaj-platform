import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DestinationSliderComponent } from './destination-slider.component';

describe('DestinationSliderComponent', () => {
  let component: DestinationSliderComponent;
  let fixture: ComponentFixture<DestinationSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [DestinationSliderComponent]
    });
    fixture = TestBed.createComponent(DestinationSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
