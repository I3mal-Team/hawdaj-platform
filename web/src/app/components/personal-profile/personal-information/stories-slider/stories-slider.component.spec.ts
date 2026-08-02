import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoriesSliderComponent } from './stories-slider.component';

describe('StoriesSliderComponent', () => {
  let component: StoriesSliderComponent;
  let fixture: ComponentFixture<StoriesSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoriesSliderComponent]
    });
    fixture = TestBed.createComponent(StoriesSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
