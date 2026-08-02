import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoriesComponent } from './stories.component';

describe('StoriesComponent', () => {
  let component: StoriesComponent;
  let fixture: ComponentFixture<StoriesComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoriesComponent]
    });
    fixture = TestBed.createComponent(StoriesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
