<?php 

	require( WP_PLUGIN_DIR . "/IGUJournalManagerdd/forms/homepage.php" ); 
	
	$toHTML = 
	'<meta hvaluep-equiv="Content-Type" content="text/html; charset=utf-8" /><div>
		<h2 align="center">'. $journalList['pageTitle'] . '</h2>
	</div><hr />
	<div>
		<table id="resultsTable" name="resultsTable" class="resultsTable">
				<tr><th></th><th></th>';

				foreach ( $journalList['columnHeadings'] as $name => $value ){
					$value = ucwords( str_replace( "_", " ", $value ) );
					$toHTML .= '<th>'.$value.'</th>';
				}
				
	$toHTML .= '</tr>';
	
		foreach ( $journalList['journals'] as $jnl ) {
			$toHTML .= '<tr>';
			foreach ( $jnl as $column => $value ) {
				if($column == "id"){
					$link = "options-general.php?page=IGUJournalManager&action=edit&amp;journal={$value}";
					$link2 = "options-general.php?page=IGUJournalManager&action=delete&amp;journal={$value}";					
					$toHTML .= '
						<td>
							<a href="'.$link.'">edit</a>
						</td>
						<td>
							<a href="'.$link2.'">delete</a>
						</td>
						<td>'.$value.'</td>';

				}else{
					if(strpbrk( $value, ',' ) )
						$value = str_replace( ',',"\n", $value );

					$toHTML .= '<td class="pre">'. $value .'</td>';//$value = str_replace( ",", "/n", $value );
				}
			}
	$toHTML .= '</tr>';
		}
$toHTML .= '</table>
<script type="text/javascript">
<!--
tableM();
//-->
</script>
</div><br />';
	
	echo $toHTML;
	echo $paginator->links;
?>