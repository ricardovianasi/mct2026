<?php

/**
 * Class UP_Player
 * @file
 */

class UP_Player {

  const PLAYER_SHOW_START_DATE_TIME     = 'player_show_start_date_time';
  const PLAYER_SHOW_END_DATE_TIME       = 'player_show_end_date_time';
  const PLAYER_EMBED                    = 'player_embed';
  const PLAYER_PLAYLIST                 = 'player_playlist_';
  const PLAYER_SAMBATECH_PROJECT_ID     = 'player_sambatech_project_id';
  const PLAYER_AGE_RANGE_ALERT          = 'player_age_range_alert';

  const PRESENTATION_START_DATE_TIME    = 'presentation_start_date_time';
  const PRESENTATION_END_DATE_TIME      = 'presentation_end_date_time';

  private static $instance;

  private $post = null;
  private $fields = [];
  private $now;

  public function __construct( $post=null ) {
    $this->setPost( $post );
    $this->now = \DateTime::createFromFormat('d/m/Y H:i:s', date_i18n('d/m/Y H:i:s'));
  }

  /**
   * @return UP_Player
   */
  public static function getInstance(): UP_Player
  {
    if( self::$instance === null ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  public function setPost( $post )
  {
    if( $post ) {
      $this->post = get_post( $post );
    }

    return $post;
  }

  public function getField( $selector )
  {
    if( !in_array( $selector, $this->fields ) ) {
      $this->fields[$selector] = get_field( $selector, $this->post );
    }

    return $this->fields[$selector];
  }

  public function debateIsPlayerOpen( $post=null ) {
    if( $post ) {
      $this->setPost( $post );
    }

    $from = $this->getField( 'presentation_start_date_time' );
    if( $from ) {
      $from = DateTime::createFromFormat( 'd/m/Y H:i:s', $from );
    } else {
      return false;
    }

    $to = $this->getField( 'presentation_end_date_time' );
    if( $to ) {
      $to = DateTime::createFromFormat( 'd/m/Y H:i:s', $to );
    }

    if(  $this->now >= $from && ( !$to || $this->now <= $to ) ) {
      return true;
    }

    return false;
  }

  public function isPlayerOpen( $post=null ): bool
  {
    if( $post ) {
      $this->setPost( $post );
    }

    $from = $this->getField( self::PLAYER_SHOW_START_DATE_TIME, $this->post );
    if( $from ) {
      $from = DateTime::createFromFormat( 'd/m/Y H:i:s', $from );
    } else {
      return false;
    }

    $to = $this->getField( self::PLAYER_SHOW_END_DATE_TIME, $this->post );
    if( $to ) {
      $to = DateTime::createFromFormat( 'd/m/Y H:i:s', $to );
    }

    if(  $this->now >= $from && ( !$to || $this->now <= $to ) ) {
      return true;
    }

    return false;
  }

  public function player( $post = null, $thumbnail = "",  )
  {
    if( $post ) {
      $this->setPost($post);
    }

    if( !$this->post ) {
      return;
    }

    if( !$this->isPlayerOpen() ) {
      return;
    }

    $player_embed = $this->getField( self::PLAYER_EMBED );
    $player_playlist = [];
    for ($i = 1; $i<10; $i++) {
      if( $media_id = $this->getField( self::PLAYER_PLAYLIST.$i )  ) {
        $player_playlist[] = trim($media_id);
      }
    }

    if( !$player_embed && !$player_playlist ) {
      return;
    }

    $player_project_id = $this->getField( self::PLAYER_SAMBATECH_PROJECT_ID );
    if( !$player_project_id ) {
      $player_project_id = 'e2973506fea631750e9ee97a4dff1bfb';
    }

    $player_age_range_alert = $this->getField( self::PLAYER_AGE_RANGE_ALERT );

    ob_start( );
    ?>
      <div class="player">
        <div class="aspect-ratio aspect-ratio--16-9">
          <?php if( $player_playlist ): ?>
            <div id="player-content"
              data-project='<?php echo $player_project_id ?>'
              data-playlist='<?php echo implode(';', $player_playlist)?>'>
            </div>
          <?php elseif( $player_embed ): ?>
            <div id="player-content"><?php echo $player_embed ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php if( $player_age_range_alert ): ?>
        <div id="classification-alert" class="popup-message" style="display:none">
          <h2>Atenção</h2>
          <p>Este filme é recomendado apenas para maiores de 18 anos. Você tem 18 anos ou mais?</p>
          <p>&nbsp;</p>
          <p class="alignright">
            <a href="<?php echo bloginfo('url') ?>" class="btn btn-small btn-red">Não</a>
            <button class="btn btn-small btn-red" data-fancybox-close>Sim</button>
          </p>
        </div>
      <?php endif; ?>
    <?php return ob_get_clean( );
  }

  public function calendarLinks($btnClass='btn btn-primary-o btn-full calendar-links')
  {
    $from = $this->getField( self::PRESENTATION_START_DATE_TIME, $this->post );
    if( $from ) {
      $from = DateTime::createFromFormat( 'd/m/Y H:i:s', $from );
    } else {
      return;
    }

    $to = $this->getField( self::PRESENTATION_END_DATE_TIME, $this->post );
    if( $to ) {
      $to = DateTime::createFromFormat( 'd/m/Y H:i:s', $to );
    }

    if( $from > $this->now ) {
     return calendar_links(
       get_the_title( $this->post ),
       $from,
       $to ? $to : $from,
       get_the_excerpt( $this->post ),
       get_the_permalink( $this->post ),
       $btnClass
     );
    }

    return;
  }

}
