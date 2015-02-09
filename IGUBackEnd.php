<?php
	/*
		Plugin Name: IGU Journals Manager
		Description: IGU Journals Manager
		Version: 1.0
	*/
	
	require_once( WP_PLUGIN_DIR . "/journals-management/class/journaldd.php" );
	require_once( WP_PLUGIN_DIR . "/journals-management/class/pagination.class.php" );

	wp_register_style( 'igu_admin_css', WP_PLUGIN_URL . "/journals-management/css/dbManagerCSS.css", "", ""  );
	wp_enqueue_style( 'igu_admin_css' );
	
	function on_admin_menu() {
		add_options_page('IGU Journals Manager', 'IGU Journals Manager', 'manage_options', 'IGUJournalManager', 'IGUJournalManager');
	}
	add_action( 'admin_menu', 'on_admin_menu' );

	function IGUJournalManager(){
	
		if( isset( $_GET[ 'action' ] ) ){
	
			switch( $_GET[ 'action' ] ){
			
				case 'new':
					newJournal();
				break;
				case 'edit':
					editJournal();
				break;
				case 'view':
					viewJournal();
				break;
				case 'delete':
					deleteJournal();
				break;
				case 'search':
					searchJournal();
				break;
				case 'upload':
					uploadJournalCSV();
				break;
				case 'success':
				break;
				default:
					homepage();
			}
		}else
			homepage();
	}
	
	function homepage(){
		require_once( WP_PLUGIN_DIR . "/journals-management/forms/homepage.php" );
	}
	
	function newJournal(){
		$journalList[] = array();
		$journalList["pageTitle"] = "Add New Journal";
		$journalList["formAction"] = "new";
	

		if( isset($_POST["saveChanges"] ) ){
			$jnl = new journaldd();
			$jnl->storeFormValues( $_POST );


			if( $jnl->insert() )
				viewJournal(); //success
			else{
				$journalList["journals"] = new journaldd();
				$journalList['columnHeadings'] = journaldd::getColumnHeadings();
				require_once( WP_PLUGIN_DIR . "/journals-management/forms/newJournal.php" );
			}
		}else{
	
			$journalList["journals"] = new journaldd();
			$journalList['columnHeadings'] = journaldd::getColumnHeadings();
			require_once( WP_PLUGIN_DIR . "/journals-management/forms/newJournal.php" );
		}
	}
	
	function saveJournal(){
		$jnl = new journal();
		$jnl->storeFormValues( $_POST );
		if( $jnl->insert() )
			return true;//viewJournal(); //success
		else
			return false;
	}
	
	function editJournal(){
	
		$journalList = array();
		$journalList['pageTitle'] = "Edit journal";
		$journalList['formAction'] = "edit";

		if ( $_POST['saveChanges'] == "Save Changes" ) {
			$jnl = new journaldd();
			$jnl->storeFormValues( $_POST );
			if( $jnl->update() ){
				$journalList['formAction'] = "view";
				viewJournal();
			}
			else{
				//viewJournal();error
				$journalList['journals'] = journaldd::viewCustom( 'id', $_POST['id'],null,null );
				$journalList['columnHeadings'] = journaldd::getColumnHeadings();
				require( WP_PLUGIN_DIR . "/journals-management/forms/editJournal.php" );
			}
		}else{
			$journalList['journals'] = journaldd::viewCustom( 'id', $_GET['journal'],null,null );
			$journalList['columnHeadings'] = journaldd::getColumnHeadings();
			require( WP_PLUGIN_DIR . "/journals-management/forms/editJournal.php" );
		}
	}
	
	function viewJournal(){
		//header('location: options-general.php?page=IGUJournalManager&amp;action=view');
		$journalList = array();
		$journalList['pageTitle'] = "View Journals";
		$journalList['formAction'] = "view";
		$paginator = new Paginator( 20 );
		$journalList['journals'] = journaldd::viewAll( $paginator->findStart(), 20 );
		$journalList['columnHeadings'] = journaldd::getColumnHeadings();
		$paginator->findPages( journaldd::calcFoundRows() );
		require( WP_PLUGIN_DIR . "/journals-management/forms/viewAllJournals.php" );
	}
	
	function deleteJournal(){
		$journalList = array();
		$journalList['formAction'] = "view";
		$jnl = new journaldd();
		//$jnl->storeFormValues( $_POST['id'] );
		if( $jnl->delete($_GET['journal']) )
			viewJournal(); //success
		else
			viewJournal(); //fail
	}
	
	function searchJournal(){
		
		$journalList = array();
		$journalList['pageTitle'] = "Search Results";
		$journalList['formAction'] = "view";
		if( isset( $_POST['search'] ) ){
			if( isset( $_POST['filter'] ) ){
				$journalList['search'] = $_POST['search'];
				$journalList['filter'] = $_POST['filter'];
				$journalList['columns'] = journaldd::getColumnHeadings();
				$paginator = new Paginator( 10 );
				$journalList['journals'] = journaldd::viewCustom( $_POST['filter'], "%".$_POST['search']."%", $paginator->findStart(), 10 );//$_POST['search'], $_POST['filter'] );
				$paginator->findPages( journaldd::calcFoundRows() );
				$journalList['columnHeadings'] = journaldd::getColumnHeadings();
				require( WP_PLUGIN_DIR . "/journals-management/forms/viewAllJournals.php" );
			}
		}/*else{
			$journalList['search'] = "canada";
			$journalList['filter'] = "all";
			$journalList['columns'] = journaldd::getColumnHeadings();
			$paginator = new Paginator( 10 );
			$journalList['journals'] = journaldd::viewCustom( $journalList['filter'], "%".$journalList['search']."%", $paginator->findStart(), 10 );//$_POST['search'], $_POST['filter'] );
			$paginator->findPages( journaldd::calcFoundRows() );
			$journalList['columnHeadings'] = journaldd::getColumnHeadings();
			require( WP_PLUGIN_DIR . "/journals-management/forms/viewAllJournals.php" );
		}*/
	}
	
	function uploadJournalCSV(){
	
		$journalList = array();
		$journalList['pageTitle'] = "Upload Journals in CSV Format";
		$journalList['formAction'] = "upload";
		
		if(  isset( $_POST['uploadForm'] ) ){
			if(file_exists($_FILES['csv_file']['tmp_name'])){
				if ( ( $handle = fopen($_FILES['csv_file']['tmp_name'], "r" ) ) !== FALSE ) {
					while ( ( $data = fgetcsv( $handle, "," ) ) !== FALSE ) {
						$jnl = new journaldd();
						$jnl->storeFormValues( $data );
						if( $jnl->insert() ){
							;
						}else{
							$error[] = $data;
						}
					}
					fclose($handle);
					if( isset( $error ) )
						;//print success with errors
				}
			}else require( WP_PLUGIN_DIR . "/journals-management/forms/uploadJournal.php" );
		}else
			require( WP_PLUGIN_DIR . "/journals-management/forms/uploadJournal.php" );
	}
?>