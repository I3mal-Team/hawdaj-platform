import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UploadImageVideoFromServerComponent } from './upload-image-video-from-server.component';

describe('UploadImageVideoFromServerComponent', () => {
  let component: UploadImageVideoFromServerComponent;
  let fixture: ComponentFixture<UploadImageVideoFromServerComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [UploadImageVideoFromServerComponent]
    })
      .compileComponents();

    fixture = TestBed.createComponent(UploadImageVideoFromServerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
