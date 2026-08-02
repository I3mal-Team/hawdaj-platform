import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeDownloadAppsSectionComponent } from './home-download-apps-section.component';

describe('HomeDownloadAppsSectionComponent', () => {
  let component: HomeDownloadAppsSectionComponent;
  let fixture: ComponentFixture<HomeDownloadAppsSectionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomeDownloadAppsSectionComponent]
    });
    fixture = TestBed.createComponent(HomeDownloadAppsSectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
