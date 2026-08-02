import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ResturantsComponent } from './resturants.component';

describe('ResturantsComponent', () => {
  let component: ResturantsComponent;
  let fixture: ComponentFixture<ResturantsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ResturantsComponent]
    });
    fixture = TestBed.createComponent(ResturantsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
