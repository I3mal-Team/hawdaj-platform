import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomePageApplicationsMobileV2Component } from './home-page-applications-mobile-v2.component';

describe('HomePageApplicationsMobileV2Component', () => {
  let component: HomePageApplicationsMobileV2Component;
  let fixture: ComponentFixture<HomePageApplicationsMobileV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomePageApplicationsMobileV2Component]
    });
    fixture = TestBed.createComponent(HomePageApplicationsMobileV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
