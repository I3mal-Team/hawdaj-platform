export interface IVideoPlayerConfig {
    fullScreen?: boolean;
    pictureInPicture?: boolean;
    seekControls?: boolean;
    speedControls?: boolean;
    lockControl?: boolean;
    seekControlsValues?: {
        backward?: number;
        forward?: number;
    };
    volumeControl?: boolean;
    canDownload?: boolean;
    progreesBar?: boolean;
    mute?: boolean;
    autoPlay?: boolean;
}