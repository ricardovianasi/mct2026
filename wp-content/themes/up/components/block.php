<?php

class Block
{
  private static $instance;
  private static $block = 'block';
  
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    
    return self::$instance;
  }
  
  public static function register() {
    if(function_exists('acf_register_block_type')) {
      acf_register_block_type(array(
        'name'              => self::$block,
        'title'             => __('Bloco Genérico'),
        'description'       => __('Bloco Genérico'),
        'render_callback'   => [self::class, 'callback'],
        'category'          => 'up',
        'icon'              => 'admin-appearance'
      ));
    }
  }
  
  /**
   * CTA Block Callback Function.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */
  public static function callback($block, $content = '', $is_preview = false, $post_id = 0) {
    $block_title = get_field('block_title');
    $block_subtitle = get_field('block_subtitle');
    $block_image = get_field('block_image');
    $block_text = get_field('block_text');
    $block_tags = get_field('block_tags');
    
    // Create id attribute allowing for custom "anchor" value.
    $id = self::$block . '-' . $block['id'];
    if( !empty($block['anchor']) ) {
      $id = $block['anchor'];
    }
    
    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'formation-block';
    if( !empty($block['className']) ) {
      $className .= ' ' . $block['className'];
    }
    
    if($is_preview) {
      $className .= ' block-preview';
    }
    ?>
    <div id="<?php echo $id ?>" class="<?php echo $className ?>">
      <?php if($is_preview): ?>
        <label for="<?php echo $id ?>" class="components-placeholder__label">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" role="img" aria-hidden="true" focusable="false">
            <path d="M16 4.2v1.5h2.5v12.5H16v1.5h4V4.2h-4zM4.2 19.8h4v-1.5H5.8V5.8h2.5V4.2h-4l-.1 15.6zm5.1-3.1l1.4.6 4-10-1.4-.6-4 10z"></path>
          </svg>
          Bloco
        </label>
      <?php endif; ?>
      <?php if(!$is_preview) {
        $block_classes[] = 'block-content max-w-[950px]';
        $block_classes[] = 'image_first';
        $block_image_size = 'block_small';
        ?>
        <div class="<?php echo implode( ' ', $block_classes ) ?>">
          <div class="block-content-wrapper">
            <?php if (!empty($block_image['ID'])): ?>
              <figure class="block-content-figure"><?php echo wp_get_attachment_image( $block_image['id'], $block_image_size ) ?></figure>
            <?php endif; ?>
            <div class="block-content-desc">
              <div class="tags">
                <?php if ($block_tags): ?>
                  <?php foreach ($block_tags as $tag) {
                    echo !empty($tag['tag']) ? '<span class="tag black">'.$tag['tag'].'</span>' : '';
                  } ?>
                <?php endif; ?>
              </div>
              <h4 class="title"><?php echo $block_title ?></h4>
              <?php if( $block_subtitle ): ?>
                <div class="subtitle"><?php echo $block_subtitle ?></div>
              <?php endif; ?>
              <?php echo $block_text ?? '' ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <?php
  }
}

add_action('acf/init', [Block::class, 'register']);
