import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RateItemComponent } from './rate-item.component';

describe('RateItemComponent', () => {
  let component: RateItemComponent;
  let fixture: ComponentFixture<RateItemComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [RateItemComponent]
    });
    fixture = TestBed.createComponent(RateItemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
