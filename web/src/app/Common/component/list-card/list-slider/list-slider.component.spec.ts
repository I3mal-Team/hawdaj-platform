import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListSliderComponent } from './list-slider.component';

describe('ListSliderComponent', () => {
  let component: ListSliderComponent;
  let fixture: ComponentFixture<ListSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ListSliderComponent]
    });
    fixture = TestBed.createComponent(ListSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
