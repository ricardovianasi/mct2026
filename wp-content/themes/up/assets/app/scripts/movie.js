(function($) {
  $.fn.upMovies = function () {
    return $(this).each(function() {
      const $sliderContainer = $(this);
      const $bgcEffect = $sliderContainer.find('.movie-bgc-effect');
      $sliderContainer.find('.movie-item[data-movie-bgc!=""][data-movie-bgc]')
      .on('mouseenter', function(e) {
        const movieBgc = $(this).attr('data-movie-bgc');
        let useTimeOut = false;

        if($bgcEffect.attr('style')) {
          useTimeOut = true;
        }

        $bgcEffect.css({
          'background-image': `url(${movieBgc})`,
          'opacity': 0
        });

        if(useTimeOut) {
          setTimeout(function () {
            $bgcEffect.css('opacity', '.24');
          }, 300);
        } else {
          $bgcEffect.css('opacity', '.24');
        }
      })
    });
  };

  $.fn.mediaplayer = function() {
    var $this = $(this),
      $mediaPlayer = $('#player-content', $this),
      playlistData = $mediaPlayer.data('playlist'),
      projectId = $mediaPlayer.data('project'),
      thumbnail = $mediaPlayer.data('thumbnail');

    if( !playlistData ) {
      return
    }

    const playlistItems = playlistData.split(';');
    let currentIndex = 0;

    const player = new SpallaPlayer('player-content', { //player é o ID do elemento html que ele vai inserir o iframe
      videoId: playlistItems[currentIndex],
      height: 360,
      width: 640,
    });

    player.on('ended', (e) => {
      currentIndex++;

      if (currentIndex < playlistItems.length) {
        const nextVideoId = playlistItems[currentIndex];
        player.changeMedia({
          videoId: nextVideoId,
          autoplay: true
        });
      }
    });

    player.on('loadingSpalla', (e) => {
      const currentVideoId = e.id;
      if (!currentVideoId) {
        return;
      }

      if (currentVideoId !== playlistItems[0]) {
        player.play();
      }
    });

  };
})(jQuery);
