import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MyFavouritesListingComponent } from './my-favourites-listing.component';

describe('MyFavouritesListingComponent', () => {
  let component: MyFavouritesListingComponent;
  let fixture: ComponentFixture<MyFavouritesListingComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MyFavouritesListingComponent]
    });
    fixture = TestBed.createComponent(MyFavouritesListingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
