import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DatepickerRangeComponent } from './datepicker-range.component';

describe('DatepickerRangeComponent', () => {
  let component: DatepickerRangeComponent;
  let fixture: ComponentFixture<DatepickerRangeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [DatepickerRangeComponent]
    });
    fixture = TestBed.createComponent(DatepickerRangeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
