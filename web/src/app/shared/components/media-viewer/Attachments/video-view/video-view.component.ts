import { Component, ElementRef, Input, ViewChild, OnInit, AfterViewInit, OnDestroy, HostListener, inject, PLATFORM_ID, Renderer2 } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { sanitizeUrl } from 'src/app/Common/functions/sanitizer.util';
import { IVideoPlayerConfig } from './configs/video-player.config';
import { IMedia } from '../../interface/media';


@Component({
  selector: 'app-video-view',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './video-view.component.html',
  styleUrls: ['./video-view.component.scss']
})
export class VideoViewComponent implements OnInit, AfterViewInit, OnDestroy {
  @Input() video: IMedia;
  @Input() config: IVideoPlayerConfig;

  private platformId = inject(PLATFORM_ID);
  private publicService = inject(PublicService)


  @ViewChild('videoElement', { static: false }) videoView!: ElementRef<HTMLVideoElement>;
  @ViewChild('volumeSlider', { static: false }) volumeSlider!: ElementRef<HTMLInputElement>;

  isPlaying: boolean = false;
  isMuted: boolean = false;
  previousVolume: number = 1;
  progress: number = 0;
  volume: number = 1;
  currentTime: string = '0:00';
  duration: string = '0:00';
  showingControls: boolean = true;
  controlTimeout: any;
  isFullscreen: boolean = false;
  isLoading: boolean = true; // Start loading state
  videoEnded: boolean = false;
  isVideoStarted: boolean = false;
  isPictureInPicture: boolean = false;
  currentLanguage: string;
  playbackSpeeds = [0.5, 1, 1.5, 2];
  currentSpeed = 1;
  observer!: IntersectionObserver;

  constructor(private sanitizer: DomSanitizer,
    private renderer: Renderer2,
    private el: ElementRef,
  ) { }


  // Start ngOnInit Method
  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      document.addEventListener('fullscreenchange', this.onFullscreenChange);
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  // End ngOnInit Method

  // Start ngAfterViewInit Method
  ngAfterViewInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.setupVideoElement();
      this.setupVolumeSlider();
      this.initIntersectionObserver();
    }
  }
  // End ngAfterViewInit Method

  // Start setupVideoElement Method
  private setupVideoElement() {
    if (!isPlatformBrowser(this.platformId) || !this.videoView) return;
    const video = this.videoView?.nativeElement;
    if (video) {
      video.addEventListener('timeupdate', () => this.updateProgress());
      video.addEventListener('loadedmetadata', () => {
        this.duration = this.formatTime(video.duration);
      });
      video.addEventListener('canplaythrough', () => {
        this.isLoading = false; // Video has loaded, we can play

      });
      video.addEventListener('waiting', () => {
        this.isLoading = true;
        this.showControls();
      });

      video.addEventListener('playing', () => {
        this.isLoading = false;
      });

      video.addEventListener('progress', () => {
        this.isLoading = video.readyState < 4;
      });
      video.onplay = () => {
        this.videoEnded = false;
      };

      video.ontimeupdate = () => {
        if (video.currentTime < video.duration) {
          this.videoEnded = false;
        }
      };

    }
  }
  // End setupVideoElement Method

  // Start setupVolumeSlider Method
  private setupVolumeSlider() {
    if (isPlatformBrowser(this.platformId) && this.volumeSlider) {
      this.updateProgressBar(this.volume);
    }
  }
  // End setupVolumeSlider Method

  // Start setVolume Method
  setVolume(event: Event) {
    const volumeValue = this.getVolumeValue(event);
    this.volume = volumeValue;
    this.videoView.nativeElement.volume = volumeValue;
    this.isMuted = volumeValue === 0;
    this.updateProgressBar(volumeValue);
  }
  // End setVolume Method

  // Start getVolumeValue Method
  private getVolumeValue(event: Event): number {

    const input = event.target as HTMLInputElement;
    return parseFloat(input.value);
  }
  // End getVolumeValue Method

  // Start updateProgressBar Method
  updateProgressBar(value: number) {
    if (this.volumeSlider) {
      this.volumeSlider.nativeElement.style.setProperty('--progress', `${value * 100}%`);
    }
  }
  // End updateProgressBar Method

  // Start togglePlay Method
  togglePlay() {
    if (!isPlatformBrowser(this.platformId) || !this.videoView || this.isLocked) return;
    const video = this.videoView?.nativeElement;

    this.isVideoStarted = true
      ;
    if (!video || this.isLoading) return;

    if (this.videoEnded) {
      video.currentTime = 0;
      this.videoEnded = false;
    }

    if (video.paused) {
      video.play();
      this.isPlaying = true;
      this.handleControlsVisibility();
    } else {
      video.pause();
      this.isPlaying = false;
      this.showingControls = true;
    }
  }
  // End togglePlay Method

  // Start handleControlsVisibility Method
  private handleControlsVisibility() {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (!video) return;

    video.onplaying = () => {
      if (!this.isLoading) {
        setTimeout(() => {
          this.showingControls = false;
        }, 2000);
      }
    };
  }

  // End handleControlsVisibility Method

  // Start showControls Method
  showControls() {
    this.showingControls = true;
    clearTimeout(this.controlTimeout);
    if (this.isPlaying && !this.isLoading) {
      this.resetControlsTimeout();
    }
  }
  // End showControls Method

  // Start hideControls Method
  hideControls() {
    if (this.isPlaying && !this.isLoading) {
      clearTimeout(this.controlTimeout);
      this.resetControlsTimeout(3000);
    }
  }

  // End hideControls Method

  // Start resetControlsTimeout Method
  private resetControlsTimeout(timeout = 2000) {
    this.controlTimeout = setTimeout(() => {
      this.showingControls = false;
    }, timeout);
  }
  // End resetControlsTimeout Method

  // Start toggleMute Method
  toggleMute() {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (!video) return;

    if (this.isMuted) {
      this.unmute(video);
    } else {
      this.mute(video);
    }
    this.isMuted = !this.isMuted;
  }
  // End toggleMute Method

  // Start unmute Method
  private unmute(video: HTMLVideoElement) {
    video.muted = false;
    video.volume = this.previousVolume;
    this.volume = this.previousVolume;
    this.updateProgressBar(this.previousVolume);
  }
  // End unmute Method

  // Start mute Method
  private mute(video: HTMLVideoElement) {
    this.previousVolume = video.volume > 0 ? video.volume : 1;
    video.muted = true;
    video.volume = 0;
    this.volume = 0;
    this.updateProgressBar(0);
  }
  // End mute Method

  // Start updateProgress Method
  updateProgress() {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (!video) return;
    this.progress = (video.currentTime / video.duration) * 100;
    this.currentTime = this.formatTime(video.currentTime);
  }
  // End updateProgress Method

  // Start seek Method
  seek(event: MouseEvent) {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (!video) return;

    const progressBar = event.currentTarget as HTMLDivElement;
    const clickX = event.offsetX;
    const progressWidth = progressBar.clientWidth;
    const newTime = (clickX / progressWidth) * video.duration;
    video.currentTime = newTime;
    setTimeout(() => this.updateProgress(), 50);
    if (this.videoEnded) {
      this.videoEnded = false;
    }
  }
  // End seek Method


  // Start formatTime Method
  formatTime(time: number): string {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  }
  // End formatTime Method

  // Start onVideoEnded Method
  onVideoEnded() {
    this.isPlaying = false;
    this.videoEnded = true;
    this.showingControls = true;
  }

  // End onVideoEnded Method

  // Start toggleFullscreen Method
  toggleFullscreen() {
    if (!isPlatformBrowser(this.platformId)) return;

    const videoContainer = this.videoView.nativeElement.parentElement;
    if (!document.fullscreenElement) {
      videoContainer?.requestFullscreen().then(() => {
        this.isFullscreen = true;
      });
    } else {
      document.exitFullscreen();
    }
  }
  // End toggleFullscreen Method
  initIntersectionObserver() {
    const video = this.videoView?.nativeElement;
    this.observer = new IntersectionObserver(
      ([entry]) => {
        if (!entry.isIntersecting && !this.isPictureInPicture) {
          video.pause();
          this.isPlaying = false;
          this.showingControls = true;
        }
      }, { threshold: 0.5 });


    if (this.videoView?.nativeElement) {
      this.observer.observe(this.videoView.nativeElement);
    }
  }

  @HostListener('document:visibilitychange', [])
  pauseOnTabChange() {
    if (isPlatformBrowser(this.platformId) && document.hidden && !this.isPictureInPicture) {
      const video = this.videoView?.nativeElement;
      video.pause();
      this.isPlaying = false;
      this.showingControls = true;

    }
  }

  // Start seekForward Method
  seekForward(seconds: number) {
    const video = this.videoView?.nativeElement;
    if (video) video.currentTime += seconds;
  }
  // End seekForward Method

  // Start seekBackward Method
  seekBackward(seconds: number) {
    const video = this.videoView?.nativeElement;
    if (video) video.currentTime -= seconds;
    if (this.videoEnded == true) {
      this.videoEnded = false;
    }
  }
  // End seekBackward Method

  // Start handleKeyboardEvent Method
  @HostListener('window:keydown', ['$event'])
  handleKeyboardEvent(event: KeyboardEvent) {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (!video) return;

    const allowedKeys = [' ', 'f', 'm', 'ArrowRight', 'ArrowLeft'];

    if (this.isLocked && allowedKeys.includes(event.key)) {
      this.showControls();
      return;
    }

    switch (event.key) {
      case ' ':
        this.togglePlay();
        event.preventDefault();
        break;
      case 'f':
        this.toggleFullscreen();
        break;
      case 'm':
        this.toggleMute();
        break;
      case 'ArrowRight':
        this.seekForward(10);
        break;
      case 'ArrowLeft':
        this.seekBackward(10);
        break;
    }
  }
  // End handleKeyboardEvent Method

  // Start togglePictureInPicture Method
  togglePictureInPicture() {
    if (!isPlatformBrowser(this.platformId)) return;

    const video = this.videoView?.nativeElement;
    if (video && document.pictureInPictureEnabled) {
      if (!document.pictureInPictureElement) {
        this.isPictureInPicture = true;
        video.requestPictureInPicture();
      } else {
        this.isPictureInPicture = false;

        document.exitPictureInPicture();
      }
    }
  }
  // End togglePictureInPicture Method

  // Start onFullscreenChange Method
  onFullscreenChange = () => {
    this.isFullscreen = !!document.fullscreenElement;
  };
  // End onFullscreenChange Method

  // Start getVideoType Method
  getVideoType(fileName: string | undefined): string {
    if (!fileName) return 'video/mp4';

    const extension = fileName.split('.').pop()?.toLowerCase();
    switch (extension) {
      case 'mp4':
        return 'video/mp4';
      case 'webm':
        return 'video/webm';
      case 'ogg':
        return 'video/ogg';
      default:
        return 'video/mp4';
    }
  }
  // End getVideoType Method

  // Start sanitizeUrl Method
  sanitizeUrl(url: string): SafeResourceUrl {
    return sanitizeUrl(url, this.sanitizer);
  }
  // End sanitizeUrl Method

  // Start setPlaybackSpeed Method
  setPlaybackSpeed(speed: number) {
    if (!isPlatformBrowser(this.platformId)) return;
    const video = this.videoView?.nativeElement;
    if (video) video.playbackRate = speed;
    this.currentSpeed = speed;
  }
  isLocked: boolean = false;

  // Start toggleLock Method
  toggleLock() {
    this.isLocked = !this.isLocked;
  }
  // End toggleLock Method

  // Start ngOnDestroy Method
  ngOnDestroy() {
    if (isPlatformBrowser(this.platformId)) {
      document.removeEventListener('fullscreenchange', this.onFullscreenChange);
      if (this.observer && this.videoView?.nativeElement) {
        this.observer.unobserve(this.videoView.nativeElement);
      }
    }

  }
  // End ngOnDestroy Method
}
