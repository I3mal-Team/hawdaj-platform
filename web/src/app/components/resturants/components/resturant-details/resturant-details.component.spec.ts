import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ResturantDetailsComponent } from './resturant-details.component';

describe('ResturantDetailsComponent', () => {
  let component: ResturantDetailsComponent;
  let fixture: ComponentFixture<ResturantDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ResturantDetailsComponent]
    });
    fixture = TestBed.createComponent(ResturantDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
