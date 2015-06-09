<?php
	/*
		Plugin Name: IGU Journals Manager
		Plugin URI: http://www.igujournals.org/journals
		Description: IGU Journals Manager
		Version: 2.0
		Author: Tawanda Muhwati
		Author URI: 
	*/
	
	/**
	* Set the wp-content and plugin urls/paths
	*/
	
	define( 'jManager_URL' , plugins_url(plugin_basename(dirname(__FILE__)).'/') );
	
	if (!class_exists('jManager')) {
		class jManager{
	
			/**
			 * Constructor
			 */
			public function __construct(){
				add_action('admin_menu', array(&$this, 'add_submenu'));
				add_action( 'admin_head', array( &$this, 'admin_header' ) );
			}
			
			public function admin_header() {
				$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
				if( 'journalsManager' != $page )
					return; 
				echo '<style type="text/css">';
				echo '.wp-list-table .column-name_of_journal { width: 9%; }';
				echo '.wp-list-table .column-city_of_publication { width: 9%; }';
				echo '.wp-list-table .column-name_of_publishing_company { width: 9%; }';
				echo '.wp-list-table .column-language { width: 8%; }';
				echo '.wp-list-table .column-isi { width: 4%; }';
				echo '.wp-list-table .column-country { width: 9%; }';
				echo '.wp-list-table .column-e_issn { width: 8%; }';
				echo '.wp-list-table .column-print_issn { width: 8%; }';
				echo '</style>';
			}
			
			public function add_submenu(){
				add_options_page('IGU Journals Manager', 'IGU Journals Manager', 'manage_options', basename(__FILE__), array(&$this, 'home'));
			}
						
			public function home(){
				
				if(!empty($_POST['submit']) && $_POST['submit'] === 'Save Journal'){
					global $wpdb;
					$current_url = $this->clear_url();
					if(!empty($_GET['paged']))
						$current_url = remove_query_arg('paged', $current_url);
					$wpdb->insert('wp_igu_journals', $this->generatePairs($_POST));
					wp_redirect($current_url);
				}
				elseif(!empty($_POST['submit']) && $_POST['submit'] === 'Update Journal'){
					global $wpdb;
					$current_url = $this->clear_url();
					$wpdb->update( 'wp_igu_journals', $this->generatePairs($_POST), array( "id" => absint($_POST['id']) ) );
					wp_redirect($current_url);
				}
				elseif(!empty($_GET['action']) && $_GET['action'] === 'new'){
					$this->newJournal();
				}
				elseif(!empty($_GET['edit']) && absint( $_GET['edit'] )){
					$this->editJournal();
				}
				elseif(!empty($_GET['delete']) && absint( $_GET['delete'] )){
					$this->deleteJournal( absint( $_GET['delete'] ) );
				}
				else{
					$this->viewJournals();
				}
			}

			private function viewJournals(){
				$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
				$jManagerListTable = new jManager_List_Table();
				$jManagerListTable->prepare_items();
				?>
					<div class="wrap">
						<form method="post">
							<input type="hidden" name="page" value="journalsManager">
							<?php $jManagerListTable->search_box('search','search_id');?>
						</form>
						<div id="icon-users" class="icon32"></div>
						<h2>Journals Manager</h2>
						<?php
							echo sprintf(
									"<a href='%s'>", 
									esc_url(add_query_arg('action', 'new', $current_url))
								);
							submit_button( __('Add New Journal'), 'button', false, false, array('id' => 'newJournal') );
							echo "</a>";
						?>
						<?php $jManagerListTable->display(); ?>
					</div>
				<?php
			}
			
			private function newJournal(){
				echo '<h2>IGU Journals Manager - Add New Journal</h2>
					 <form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label for="name_of_journal">Journal Name <span class="description">(required)</span></label></th>
									<td><input type="text" id="" name="name_of_journal" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="country"><strong>Country</strong></label></th>
									<td><input type="text" id="" name="country" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="print_issn"><strong>ISSN</strong></label></th>
									<td><input type="text" id="" size="10" name="print_issn" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="e_issn"><strong>eISSN</strong></label></th>
									<td><input type="text" id="" size="10" name="e_issn" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="city_of_publication"><strong>City Published</strong></label></th>
									<td><input type="text" id="" size="50" name="city_of_publication" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="name_of_publishing_company"><strong>Publishing Company</strong></label></th>
									<td><input type="text" id="" size="50" name="name_of_publishing_company" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="editor"><strong>Editor</strong></label></th>
									<td><input type="text" id="" size="50" name="editor" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="editor_info"><strong>Editor Info</strong></label></th>
									<td><input type="text" id="" size="50" name="editor_info" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="language"><strong>Language</strong></label></th>
									<td><input type="text" id="" size="50" name="language" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="since"><strong>Since</strong></label></th>
									<td><input type="text" id="" size="50" name="since" value="1900" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="isi"><strong>ISI</strong></label></th>
									<td><input type="text" id="" size="50" name="isi" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="isi_category"><strong>ISI Category</strong></label></th>
									<td><input type="text" id="" size="50" name="isi_category" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="impact_factor"><strong>Impact factor</strong></label></th>
									<td><input type="text" id="" size="50" name="impact_factor" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="website"><strong>Website</strong></label></th>
									<td><input type="text" id="" size="50" name="website" class="regular-text"/></td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Journal">
						</p>
					</form>';
			}
			
			private function editJournal(){
				global $wpdb;
				
				$id = absint( $_GET['edit'] );
				
				$sql = "SELECT * FROM wp_igu_journals WHERE id LIKE '$id'";
				$dat = $wpdb->get_results( $sql, ARRAY_A );
				$data = $dat[0];
			
				echo '<h2>IGU Journals Manager - Edit Journal</h2>
					 <form method="post">
						<input type="hidden" id="id" name="id" value="'.$data["id"].'" />
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label for="name_of_journal">Journal Name <span class="description">(required)</span></label></th>
									<td><input type="text" id="name_of_journal" name="name_of_journal" value="'.$data["name_of_journal"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="country"><strong>Country</strong></label></th>
									<td><input type="text" id="" name="country" value="'.$data["country"].'" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="print_issn"><strong>ISSN</strong></label></th>
									<td><input type="text" id="" size="10" name="print_issn" value="'.$data["print_issn"].'" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="e_issn"><strong>eISSN</strong></label></th>
									<td><input type="text" id="" size="10" name="e_issn" value="'.$data["e_issn"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="city_of_publication"><strong>City Published</strong></label></th>
									<td><input type="text" id="" size="50" name="city_of_publication" value="'.$data["city_of_publication"].'" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="name_of_publishing_company"><strong>Publishing Company</strong></label></th>
									<td><input type="text" id="" size="50" name="name_of_publishing_company" value="'.$data["name_of_publishing_company"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="editor"><strong>Editor</strong></label></th>
									<td><input type="text" id="" size="50" name="editor" value="'.$data["editor"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="editor_info"><strong>Editor Info</strong></label></th>
									<td><input type="text" id="" size="50" name="editor_info" value="'.$data["editor_info"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="language"><strong>Language</strong></label></th>
									<td><input type="text" id="" size="50" name="language" value="'.$data["language"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="since"><strong>Since</strong></label></th>
									<td><input type="text" id="" size="50" name="since" value="'.$data["since"].'" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="isi"><strong>ISI</strong></label></th>
									<td><input type="text" id="" size="50" name="isi" value="'.$data["isi"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="isi_category"><strong>ISI Category</strong></label></th>
									<td><input type="text" id="" size="50" name="isi_category" value="'.$data["isi_category"].'" class="regular-text"/></td>
								</tr>
								<tr>
									<th scope="row"><label for="impact_factor"><strong>Impact factor</strong></label></th>
									<td><input type="text" id="" size="50" name="impact_factor" value="'.$data["impact_factor"].'" class="regular-text"/></td>	
								</tr>
								<tr>
									<th scope="row"><label for="website"><strong>Website</strong></label></th>
									<td><input type="text" id="" size="50" name="website" value="'.$data["website"].'" class="regular-text"/></td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Journal">
						</p>
					</form>';
			}
			
			private function deleteJournal( $id ){
				global $wpdb;
				$wpdb->query("DELETE FROM wp_igu_journals WHERE id = '$id' ");
				$this->viewJournals();
			}
			
			private function clear_url(){
				$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
				if(!empty($_GET['action']))
					$current_url = remove_query_arg('action', $current_url);
				if(!empty($_GET['edit']))
					$current_url = remove_query_arg('edit', $current_url);
				if(!empty($_GET['delete']))
					$current_url = remove_query_arg('delete', $current_url);
				return $current_url;
			}
			
			private function generatePairs($data){
				return array(
					"country" => $data['country'],
					"name_of_journal" => $data['name_of_journal'],
					"print_issn" => $data['print_issn'],
					"e_issn" => $data['e_issn'],
					"city_of_publication" => $data['city_of_publication'],
					"name_of_publishing_company" => $data['name_of_publishing_company'],
					"editor" => $data['editor'],
					"editor_info" => $data['editor_info'],
					"language" => $data['language'],
					"since" => $data['since'],
					"isi" => $data['isi'],
					"isi_category" => $data['isi_category'],
					"5_year_impact_factor" => $data['impactFactor'],
					"website" => $data['website']
				);
			}
		}
	}

	if (class_exists('jManager') && is_admin()) {
		$jManager = new jManager();
	}
	
	if(!class_exists('WP_List_Table')){
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
	}
	
	class jManager_List_Table extends WP_List_Table{
		
		public function prepare_items(){
			$columns               = $this->get_columns();
			$hidden                = $this->get_hidden_columns();
			$sortable              = $this->get_sortable_columns();
			
			$perPage = 1000;
			$total = 1500;
			$currentPage = $this->get_pagenum();
			
			$this->set_pagination_args( array(
				'total_items' => $total,
				'per_page' => $perPage
			) );
			
			$data                  = $this->table_data();
			
			$this->_column_headers = array($columns, $hidden, $sortable);
			
			if(!empty($_POST['s'])){
				global $wpdb;
				$value = $_POST['s'];
				
				$where = "WHERE country LIKE '".$value."' OR name_of_journal LIKE '".$value."' OR  print_issn LIKE '".$value."' OR e_issn LIKE '".$value."' OR city_of_publication LIKE '".$value."' OR name_of_publishing_company LIKE '".$value."' OR editor  LIKE '".$value."' OR editor_info  LIKE '".$value."' OR language  LIKE '".$value."' OR isi_category LIKE '".$value."' OR since LIKE '".$value."'";
				$sql = "SELECT * FROM wp_igu_journals {$where}";				
				
				$this->items = $wpdb->get_results( $sql, ARRAY_A );
			}
			else
				$this->items           = $data;
		}
		
		public function get_columns(){
			$columns = array(
				'cb' => '',
				'id' => 'id',
				'name_of_journal' => 'Journal Name',
				'country' => 'Country',
				'print_issn' => 'ISSN',
				'e_issn' => 'e_ISSN',
				'city_of_publication' => 'City Published',
				'name_of_publishing_company' => 'Publishing Company',
				'editor' => 'Editor',
				'editor_info' => 'Editor info',
				'language' => 'Language',
				'since' => 'Since',
				'isi' => 'ISI',
				'isi_category' => 'ISI Category',
				'impactFactor' => 'Impact Factor',
				'website' => 'Website'
			);
			return $columns;
		}
		
		public function get_sortable_columns(){
			return array(
				array('country' => array('orderby', true))
			);
		}
		
		public function get_hidden_columns(){
			return array('id' => 'id');
		}
		
		private function table_data(){
			global $wpdb;
			
			$perPage = $this->get_pagination_arg('per_page');
			$start = 0;
			$end = $perPage;

			if(!empty($_GET['paged'])){
				$start = ($_GET['paged']-1)*$perPage;
				$end = $perPage;
			}
			
			$sql = 'SELECT * FROM wp_igu_journals LIMIT '.$start.','.$end;
			
			return $wpdb->get_results( $sql, ARRAY_A );
		}
		
		private function sort_data($a, $b){
		
			$orderby = 'country';
			$order = 'asc';
			
			if(!empty($_GET['orderby'])){
				$orderby = $_GET['orderby'];
			}
			
			if(!empty($_GET['order'])){
				$order = $_GET['order'];
			}
		
			$result = strnatcmp( $a[$orderby], $b[$orderby]);
			
			if($order === 'asc'){
				return $result;
			}
			
			return -$result;
		}
		
		public function column_default($item, $column_name){
			switch( $column_name ){
				case 'id':
				case 'name_of_journal':
				case 'country':
				case 'print_issn':
				case 'e_issn':
				case 'city_of_publication':
				case 'name_of_publishing_company':
				case 'editor':
				case 'editor_info':
				case 'language':
				case 'since':
				case 'isi':
				case 'isi_category':
				case 'impactFactor':
				case 'website':
					return $item[ $column_name ];
				default:
					return print_r( $item, true );
			}
		}
		
		public function column_name_of_journal($item){
			$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			$actions = array(
				'edit' => sprintf("<a href='%s'>%s</a>", esc_url(add_query_arg('edit', $item['id'], $current_url)) , 'Edit'),
				'delete' => sprintf("<a href='%s'>%s</a>", esc_url(add_query_arg('delete', $item['id'], $current_url)) , 'Delete'),
			);
			
			return sprintf('%1$s %2$s',  $item['name_of_journal'], $this->row_actions($actions));
		}
		
		public function column_website($item){
			return sprintf('<a href="%s">%s</a>', esc_url($item['website']), $item['website']);
		}
		
		public function column_cb($item){
			return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['id']);
		}
		
		public function get_bulk_actions(){
			$actions = array(
				'delete' => 'Delete'
			);
			return $actions;
		}
		
		public function no_items(){
			_e( 'No Journals found..' );
		}
	}
?>
