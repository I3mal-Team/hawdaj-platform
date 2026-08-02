import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoryImageVideoModalComponent } from './story-image-video-modal.component';

describe('StoryImageVideoModalComponent', () => {
  let component: StoryImageVideoModalComponent;
  let fixture: ComponentFixture<StoryImageVideoModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoryImageVideoModalComponent]
    });
    fixture = TestBed.createComponent(StoryImageVideoModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
