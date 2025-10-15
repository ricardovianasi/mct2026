<?php
/**
 * Plugin Name: UP - Sistema de inscrições
 * Version: 0.1
 * Plugin URI:  https://universoproducao.com.br
 * Description: Capture user subscriptions
 * Author: Ricardo Viana
 *
 */

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class UP_Subscription_Table extends WP_List_Table
{

  public function __construct()
  {
    parent::__construct([
      'singular' => __('Inscrições'),
      'plural' => __('Inscrições'),
      'ajax' => false
    ]);
  }

  public function get_subscriptions($per_page = 20, $page_number = 1)
  {
    global $wpdb;

    $sql = "SELECT 
                id, 
                nome, 
                email, 
                parent,
                id as indicacoes,
                created_at 
            FROM {$wpdb->prefix}up_subscription";

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


    $result = $wpdb->get_results($sql, 'ARRAY_A');

    return $result;
  }

  /**
   * Returns the count of records in the database.
   *
   * @return null|string
   */
  public static function record_count()
  {
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}up_subscription";

    return $wpdb->get_var($sql);
  }

  /**
   * Delete a customer record.
   *
   * @param int $id customer ID
   */
  public static function delete_subscription($id)
  {
    global $wpdb;

    $wpdb->delete(
      "{$wpdb->prefix}up_subscription",
      ['id' => $id],
      ['%d']
    );
  }

  /**
   *  Associative array of columns
   *
   * @return array
   */
  function get_columns()
  {
    $columns = [
      'cb' => '<input type="checkbox" />',
      'nome' => __('Nome'),
      'email' => __('E-mail'),
      'parent' => __('Indicado por'),
      'indicacoes' => __('Total de indicações'),
      'created_at' => __('Data da inscrição')
    ];

    return $columns;
  }

  /**
   * Render a column when no column specific method exist.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'nome':
      case 'email':
      case 'parent':
      case 'indicacoes':
      case 'created_at':
        return $item[$column_name];
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  /**
   * Render the bulk edit checkbox
   *
   * @param array $item
   *
   * @return string
   */
  function column_cb($item)
  {
    return sprintf(
      '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
    );
  }

  /**
   * Method for name column
   *
   * @param array $item an array of DB data
   *
   * @return string
   */
  function column_nome($item)
  {
    $delete_nonce = wp_create_nonce('wp_subscription_delete');

    $title = '<strong>' . $item['nome'] . '</strong>';

    $actions = [
      'delete' => sprintf(
        '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Apagar</a>',
        esc_attr($_REQUEST['page']), 'delete', absint($item['id']),
        $delete_nonce
      )
    ];

    return $title . $this->row_actions($actions);
  }

  function column_parent($item)
  {
    $parent_id = $item['parent'];
    if (!$parent_id) {
      return;
    }

    global $wpdb;

    $sql = $wpdb->prepare(" SELECT id as ID, nome, email, parent, created_at FROM {$wpdb->prefix}up_subscription where id = %d", $parent_id);
    $parent = $wpdb->get_row($sql);
    if ($parent) {
      return $parent->nome . '<br />' . $parent->email;
    }
  }

  function column_indicacoes($item)
  {
    global $wpdb;

    $sql = "SELECT 
                COUNT(*) 
            FROM {$wpdb->prefix}up_subscription
            WHERE parent = {$item['id']} AND id != {$item['id']}";

    return $wpdb->get_var($sql);
  }

  public function no_items()
  {
    return 'Nenhuma inscrição disponível';
  }

  /**
   * Columns to make sortable.
   *
   * @return array
   */
  public function get_sortable_columns()
  {
    $sortable_columns = array(
      'nome' => array('nome', false),
      'email' => array('email', false),
      'created_at' => array('created_at', true),
    );

    return $sortable_columns;
  }

  /**
   * Returns an associative array containing the bulk action
   *
   * @return array
   */
  public function get_bulk_actions()
  {
    $actions = [
      'bulk-delete' => __('Apagar')
    ];

    return $actions;
  }

  /**
   * Handles data query and filter, sorting, and pagination.
   */
  public function prepare_items()
  {

    $this->_column_headers = $this->get_column_info();

    /** Process bulk action */
    $this->process_bulk_action();

    $per_page = $this->get_items_per_page('subscriptions_per_page', 20);
    $current_page = $this->get_pagenum();
    $total_items = self::record_count();

    $this->set_pagination_args([
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page' => $per_page //WE have to determine how many items to show on a page
    ]);

    $this->items = self::get_subscriptions($per_page, $current_page);
  }

  public function process_bulk_action()
  {

    //Detect when a bulk action is being triggered...
    if ('delete' === $this->current_action()) {

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr($_REQUEST['_wpnonce']);

      if (!wp_verify_nonce($nonce, 'wp_subscription_delete')) {
        die('Go get a life script kiddies');
      } else {
        self::delete_subscription(absint($_GET['subscription']));

        // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
        // add_query_arg() return the current url
        wp_redirect(esc_url_raw(add_query_arg()));
        //exit;
      }

    }

    // If the delete bulk action is triggered
    if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
      || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
    ) {

      $delete_ids = esc_sql($_POST['bulk-delete']);

      // loop over the array of record IDs and delete them
      foreach ($delete_ids as $id) {
        self::delete_subscription($id);

      }

      // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
      // add_query_arg() return the current url
      wp_redirect(esc_url_raw(add_query_arg()));
      exit;
    }
  }

  public function export()
  {
      global $wpdb;
      
      $sql = "SELECT 
               S.nome,
               S.email,
               S.data,
               S.created_at,
               (select CONCAT(P.nome, ' - ', P.email) from {$wpdb->prefix}up_subscription P where P.id = S.parent) as parent
              FROM {$wpdb->prefix}up_subscription S;";
      
      $subscriptions = $wpdb->get_results($sql, 'ARRAY_A');
      if( !$subscriptions ) {
          return;
      }

      //$csv = 'Nome,Email,Whatsapp,Faixa Etária,País,Estado,Cidade,Segmento de Atuação' . PHP_EOL;
      $csv = 'Nome,Email,Whatsapp,Faixa Etária,Estado,Cidade,País,Segmento,Indicado por, Data de cadastro' . PHP_EOL;
      foreach ( $subscriptions as $sub ) {
          $data = json_decode( $sub['data'] );

          $row = [];
          $row[] = str_replace( ',', '', $sub['nome'] );
          $row[] = str_replace( ',', '', $sub['email'] );
          $row[] = str_replace( ',', '', $data->whatsapp );
          $row[] = str_replace( ',', '', $data->faixaEtaria );
          $row[] = str_replace( ',', '', $data->estado );
          $row[] = str_replace( ',', '', $data->cidade );
          $row[] = str_replace( ',', '', $data->pais );
          $row[] = str_replace( ',', '', $data->segmento );
          $row[] = str_replace( ',', '', $sub['parent'] );
          $row[] = str_replace( ',', '', $sub['created_at'] );
          $csv .= implode( ',', $row ) . PHP_EOL;
      }

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private", false);
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"inscricoes.csv\";" );
      header("Content-Transfer-Encoding: binary");

      echo $csv;
      exit;
  }
}

class UP_Subscription_Winners_Table extends WP_List_Table
{
  public function __construct()
  {
    parent::__construct([
      'singular' => 'Vencedor',
      'plural' => 'Vencedores',
      'ajax' => false //does this table support ajax?
    ]);
  }

  public function get_subscriptions_winners($per_page = 20, $page_number = 1)
  {
    global $wpdb;

    $sql = "SELECT id, subscription_id, award, created_at FROM {$wpdb->prefix}up_subscription_winners";

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


    $result = $wpdb->get_results($sql, 'ARRAY_A');

    return $result;
  }

  /**
   * Returns the count of records in the database.
   *
   * @return null|string
   */
  public static function record_count()
  {
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}up_subscription_winners";

    return $wpdb->get_var($sql);
  }

  /** Text displayed when no customer data is available */
  public function no_items()
  {
    _e('Nenhum prêmio encontrado');
  }

  /**
   * Render a column when no column specific method exist.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'nome':
      case 'award':
      case 'created_at':
        return $item[$column_name];
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  /**
   *  Associative array of columns
   *
   * @return array
   */
  function get_columns()
  {
    $columns = [
      'cb' => '<input type="checkbox" />',
      'nome' => __('Nome', 'sp'),
      'award' => __('Prêmio', 'sp'),
      'created_at' => __('Data', 'sp')
    ];

    return $columns;
  }

  /**
   * Render the bulk edit checkbox
   *
   * @param array $item
   *
   * @return string
   */
  function column_cb($item)
  {
    return sprintf(
      '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
    );
  }

  function column_nome($item)
  {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}up_subscription WHERE id = {$item['subscription_id']}";
    $sub = $wpdb->get_row($sql);
    if ($sub) {
      return $sub->nome . '<br />' . $sub->email;
    } else {
      return 'Erro ao buscar nome do vencedor.';
    }
  }

  function column_award($item)
  {
    return '<strong>' . $item['award'] . '</strong> indicações';
  }

  /**
   * Columns to make sortable.
   *
   * @return array
   */
  public function get_sortable_columns()
  {
    $sortable_columns = array(
      'award' => array('award', true),
      'created_at' => array('created_at', false)
    );

    return $sortable_columns;
  }

  public function prepare_items()
  {
    $per_page = 20;
    $current_page = $this->get_pagenum();
    $total_items = self::record_count();

    $this->set_pagination_args([
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page' => $per_page //WE have to determine how many items to show on a page
    ]);

    $this->items = self::get_subscriptions_winners($per_page, $current_page);

    $this->_column_headers = [
      $this->get_columns(),
      [],
      $this->get_sortable_columns()
    ];
  }

  public function export()
  {
    global $wpdb;

    $sql = "SELECT 
            S.nome,
            S.email,
            W.award,
            W.created_at
        FROM {$wpdb->prefix}up_subscription_winners W
        INNER JOIN {$wpdb->prefix}up_subscription S ON S.id = W.subscription_id;";
    $subscriptions = $wpdb->get_results($sql, 'ARRAY_A');
    if( !$subscriptions ) {
      return;
    }

    $csv = 'Nome,Email,Premio,Data' . PHP_EOL;
    foreach ( $subscriptions as $sub ) {
      $row = [];
      $row[] = str_replace( ',', '', $sub['nome'] );
      $row[] = str_replace( ',', '', $sub['email'] );
      $row[] = str_replace( ',', '', $sub['award'] );
      $row[] = str_replace( ',', '', $sub['created_at'] );
      $csv .= implode( ',', $row ) . PHP_EOL;
    }

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"premiacoes.csv\";" );
    header("Content-Transfer-Encoding: binary");

    echo $csv;
    exit;
  }

}

class UP_Subscription_Plugin
{

  // class instance
  static $instance;

  /**
   * @var UP_Subscription_Table
   */
  public $subscription_table;

  /**
   * @var UP_Subscription_Winners_Table
   */
  public $subscription_winners_table;

  public function __construct()
  {
    add_action('admin_menu', [$this, 'plugin_menu']);
    //add_action('admin_init', [$this, 'subscription_export']);
  }

  /**
   * Singleton
   * @return $this
   */
  public static function get_instance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  public function plugin_menu()
  {
    $hook = add_menu_page(
      'Inscrições',
      'Inscrições',
      'manage_options',
      'up_subscription',
      [$this, 'plugin_content_page']
    );
    add_action("load-$hook", [$this, 'screen_option']);
    add_action("load-$hook", [$this, 'subscription_export']);

    $hook2 = add_submenu_page(
      'up_subscription',
      'Vencedores',
      'Vencedores',
      'manage_options',
      'up_subscription_winners',
      [$this, 'plugin_content_page_winners']
    );
    add_action("load-$hook2", [$this, 'subscription_winners_export']);

  }

  /**
   * Screen options
   */
  public function screen_option()
  {
    $option = 'per_page';
    $args = [
      'label' => 'Inscrições',
      'default' => 5,
      'option' => 'subscriptions_per_page'
    ];
    add_screen_option($option, $args);

    $this->subscription_table = new UP_Subscription_Table();
  }

  public function plugin_content_page()
  {
    ?>
      <div class="wrap">
          <h1 class="wp-heading-inline">Inscrições</h1>
          <div id="poststuff">
              <div id="post-body" class="metabox-holder">
                  <div id="post-body-content">
                      <div class="meta-box-sortables ui-sortable">
                          <form class="posts-filter" method="post" action="">
                              <p class="search-box">
                                <?php wp_nonce_field('wp-subscription-export', '_wplnonce'); ?>
                                  <input type="hidden" name="action" value="subscription-export"/>
                                  <input type="submit" name="submit" id="submit" class="button"
                                         value="Download Inscrições">
                              </p>
                          </form>
                          <form method="post">
                            <?php
                            $this->subscription_table->prepare_items();
                            $this->subscription_table->display(); ?>
                          </form>
                      </div>
                  </div>
              </div>
              <br class="clear">
          </div>
      </div>
    <?php
  }

  public function plugin_content_page_winners()
  {
    $this->subscription_winners_table = new UP_Subscription_Winners_Table();
    ?>
      <div class="wrap">
          <h1 class="wp-heading-inline">Vencedores</h1>
          <div id="poststuff">
              <div id="post-body" class="metabox-holder">
                  <div id="post-body-content">
                      <div class="meta-box-sortables ui-sortable">
                          <form class="posts-filter" method="post" action="">
                              <p class="search-box">
                                <?php wp_nonce_field('wp-subscription-winners-export', '_wplnonce'); ?>
                                  <input type="hidden" name="action" value="subscription-winners-export"/>
                                  <input type="submit" name="submit" id="submit" class="button"
                                         value="Download Vencedores">
                              </p>
                          </form>
                          <form method="post">
                            <?php
                            $this->subscription_winners_table->prepare_items();
                            $this->subscription_winners_table->display();
                            ?>
                          </form>
                      </div>
                  </div>
              </div>
              <br class="clear">
          </div>
      </div>
    <?php
  }

  public function subscription_export()
  {
    /* Listen for form submission */
    if (empty($_POST['action']) || 'subscription-export' !== $_POST['action']) {
      return;
    }

    /* Check permissions and nonces */
    if (!current_user_can('manage_options')) {
      wp_die('');
    }

    check_admin_referer('wp-subscription-export', '_wplnonce');

    return $this->subscription_table->export();

  }

  public function subscription_winners_export()
  {
    /* Listen for form submission */
    if (empty($_POST['action']) || 'subscription-winners-export' !== $_POST['action']) {
      return;
    }

    /* Check permissions and nonces */
    if (!current_user_can('manage_options')) {
      wp_die('');
    }

    check_admin_referer('wp-subscription-winners-export', '_wplnonce');

    if( !$this->subscription_winners_table ) {
      $this->subscription_winners_table = new UP_Subscription_Winners_Table();
    }

    return $this->subscription_winners_table->export();

  }

}

if (class_exists('UP_Subscription_Plugin')) {
  add_action('plugins_loaded', function () {
    UP_Subscription_Plugin::get_instance();
  });
}