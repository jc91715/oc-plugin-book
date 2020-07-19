var player= videojs(document.querySelector('.video-js'),{
    playbackRates: [0.5, 1, 1.5, 2]
});
player.ready(function () {
    this.hotkeys({
        seekStep: 5,
        volumeStep: 0.1,
        enableModifiersForNumbers: false
    });
});
