import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListCarouselComponent } from './list-carousel.component';

describe('ListCarouselComponent', () => {
  let component: ListCarouselComponent;
  let fixture: ComponentFixture<ListCarouselComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ListCarouselComponent]
    });
    fixture = TestBed.createComponent(ListCarouselComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
