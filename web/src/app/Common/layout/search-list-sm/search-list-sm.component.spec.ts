import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SearchListSmComponent } from './search-list-sm.component';

describe('SearchListSmComponent', () => {
  let component: SearchListSmComponent;
  let fixture: ComponentFixture<SearchListSmComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SearchListSmComponent]
    });
    fixture = TestBed.createComponent(SearchListSmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
