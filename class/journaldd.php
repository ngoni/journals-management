<?php
	class journaldd{
	
		public $id = null;
		public $name_of_journal = null;
		public $country = null;
		public $print_issn = null;
		public $e_issn = null;
		public $city_of_publication = null;
		public $name_of_publishing_company = null;
		public $editor = null;
		public $editor_email_address = null;
		public $language = null;
		public $since = null;
		public $isi = null;
		public $isi_category = null;
		public $impactFactor = null;
		public $website = null;
		var $tableName = "wp_igu_journals";
		
		
		public function __construct( $data = array() ){
		
			if( isset($data) ){	
				if( isset( $data['id'] ) ) 							$this->id = $data['id'];
				if( isset( $data['name_of_journal'] ) && 
					strlen( $data['name_of_journal'] ) !== 0 ) 						$this->name_of_journal = $data['name_of_journal'];
				if( isset( $data['country'] ) ) 					$this->country = $data['country'];
				if( isset( $data['print_issn'] ) ) 					$this->print_issn = $data['print_issn'];
				if( isset( $data['e_issn'] ) ) 						$this->e_issn = $data['e_issn'];
				if( isset( $data['city_of_publication'] ) ) 		$this->city_of_publication = $data['city_of_publication'];
				if( isset( $data['name_of_publishing_company'] ) ) 	$this->name_of_publishing_company = $data['name_of_publishing_company'];
				if( isset( $data['editor']) )  						$this->editor = $data['editor'];
				if( isset( $data['editor_email_address'] ) )					$this->editor_email_address = $data['editor_email_address'];
				if( isset( $data['language'] ) ) 					$this->language = $data['language'];
				if( isset( $data['since'] ) ) 						$this->since = (int)$data['since'];
				if( isset( $data['isi'] ) ) 						$this->isi = (int)$data['isi'];
				if( isset( $data['isi_category'] ) ) 				$this->isi_category = $data['isi_category'];
				if( isset( $data['5_year_impact_factor'] ) ) 		$this->impactFactor = (float)$data['5_year_impact_factor'];
				if( isset( $data['website'] ) ) 					$this->website = $data['website'];
			}
		}
		
		public function build( $data = array() ){
		
			if( isset($data) ){	
				if( isset( $data[0] ) )  $this->country = $data[0];
				if( isset( $data[1] ) && strlen( $data[1] ) !== 0 )  $this->name_of_journal = $data[1];
				if( isset( $data[2] ) )  $this->print_issn = $data[2];
				if( isset( $data[3] ) )  $this->e_issn = $data[3];
				if( isset( $data[4] ) )  $this->city_of_publication = $data[4];
				if( isset( $data[5] ) )  $this->name_of_publishing_company = $data[5];
				if( isset( $data[6] ) )  $this->editor = $data[6];
				if( isset( $data[7] ) )  $this->editor_email_address = $data[7];
				if( isset( $data[8] ) )  $this->language = $data[8];
				if( isset( $data[9] ) )  $this->since = (int)$data[9];
				if( isset( $data[10] ) ) $this->isi = (int)$data[10];
				if( isset( $data[11] ) ) $this->isi_category = $data[11];
				if( isset( $data[12] ) ) $this->impactFactor = (float)$data[12];
				if( isset( $data[13] ) ) $this->website = $data[13];
			}
		}
		
		public function storeFormValues( $data = null ){
			if( array_key_exists('name_of_journal',$data) )
				$this->__construct( $data );
			elseif( count($data) >= 14 )
				$this->build( $data );
			else
				return null;
		}
		
		private function generatePairs(){
			return array(
				"id" => $this->id,
				"country" => $this->country,
				"name_of_journal" => $this->name_of_journal,
				"print_issn" => $this->print_issn,
				"e_issn" => $this->e_issn,
				"city_of_publication" => $this->city_of_publication,
				"name_of_publishing_company" => $this->name_of_publishing_company,
				"editor" => $this->editor,
				"editor_email_address" => $this->editor_email_address,
				"language" => $this->language,
				"since" => $this->since,
				"isi" => $this->isi,
				"isi_category" => $this->isi_category,
				"5_year_impact_factor" => $this->impactFactor,
				"website" => $this->website
			);
		}
		
		public function delete($id=null){
			global $wpdb;
			return $wpdb->query("DELETE FROM wp_igu_journals WHERE id = '$id' ");
			//return $wpdb->query( $wpdb->prepare("DELETE FROM wp_igu_journals WHERE id = %d", $this->id ) );
			
		}
		
		public function insert(){
			global $wpdb;
			if( isset( $this->name_of_journal ) )
				return $wpdb->insert( $this->tableName, $this->generatePairs() );
			else
				return null;
		}
		
		function update(){
			global $wpdb;
			if( isset( $this->name_of_journal ) )
				return $wpdb->update( "wp_igu_journals", $this->generatePairs(), array( "id" => $this->id ) );
			else
				return null;
		}
		//function delete(){;}
		
		public static function viewAll( $startLimit = null, $endLimit = null ){
			global $wpdb;
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM wp_igu_journals";
			if( isset( $startLimit ) && isset( $endLimit ) ){
				$sql .= " LIMIT ".(int)$startLimit.",".(int)$endLimit;
			}
			return $wpdb->get_results( $sql, ARRAY_A );
		}
		
		public static function viewCustom( $column, $value, $startLimit, $endLimit ){
			global $wpdb;
			$sql = null;
			
			if( isset($column) && isset($value) )
				if( $column == "all" ){
					$where = "WHERE country LIKE '".$value."' OR name_of_journal LIKE '".$value."' OR  print_issn LIKE '".$value."' OR e_issn LIKE '".$value."' OR city_of_publication LIKE '".$value."' OR name_of_publishing_company LIKE '".$value."' OR editor  LIKE '".$value."' OR editor_email_address  LIKE '".$value."' OR language  LIKE '".$value."' OR isi_category LIKE '".$value."'";
					$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM wp_igu_journals {$where}";
				}else 
					$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM wp_igu_journals WHERE {$column} LIKE '{$value}'";
			else
				return null;
			
			if( isset( $startLimit ) && isset( $endLimit ) && isset($sql) )
				$sql .= " LIMIT ".(int)$startLimit.",".(int)$endLimit;

			return $wpdb->get_results( $sql, ARRAY_A );
		}
		
		public static function calcFoundRows(){
			global $wpdb;
			return $wpdb->get_var( "SELECT FOUND_ROWS();" );
		}
		
		public static function getColumnHeadings(){
			global $wpdb;
			$wpdb->get_results("SELECT * FROM wp_igu_journals");
			return $wpdb->get_col_info();
		}
		
		public static function reset(){
			global $wpdb;
			$wpdb->flush();
		}
	
	}
?>