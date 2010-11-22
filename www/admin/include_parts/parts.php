<?
class Parts {
	function SelectCategories(){
		$strTopCarrusel='
		<style type="text/css">
		.select_category{
			display:block;
			left:155px;
			top:105px;
		}
		</style>
		<div class="select_category">
		<form method="GET" action="" name="frmCat">
			<table>
				<tr>
					<td><input type="hidden" name="p" value="'.$_REQUEST["p"].'"/>
						Canal			
					</td>
					<td>
						<select name="category" onchange="document.frmCat.submit();">';
		
		$lngIdCategoria = isset($_GET['category']) && ctype_digit($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : 0;
		$strTopCarrusel .='		<option value="0"  ';
		$strTopCarrusel .= @$row['id']==0 ? "selected" : "";
		$strTopCarrusel .='>Portada</option>';
		
		$strSQL = "SELECT id, title FROM categories WHERE parent_id = 0 ORDER BY title";
		$result = mysql_query($strSQL);

		$strOptions = "";
		while($row = mysql_fetch_array($result)){
					$strOptions .='<option value="'.$row['id'].'"';
					$strOptions .= $row['id']==$lngIdCategoria ? "selected" : "";
					$strOptions .='>'.$row['title'].'</option>';
		}
		
		$strTopCarrusel .= $strOptions;
		$strTopCarrusel .='		</select>
					</td>
				</tr>
			</table>
		</form>
		</div>';
		return $strTopCarrusel;
	}
	
	function IncludeAutocomplete(){
		$strJS= 
<<<FIN
<script type='text/javascript' src='../js/framework/source/jquery.js'></script>
<script type='text/javascript' src='../js/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../js/lib/source/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../js/lib/source/jquery.autocomplete.js'></script>
<script type='text/javascript' src='../js/api/thickbox-compressed.js'></script>
<link rel="stylesheet" type="text/css" href="../css/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../css/thickbox.css" />
<script type="text/javascript">
$().ready(function() {
	function findValueCallback(event, data, formatted) {
		$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
	}
	
	function formatItem(row) {
		return row[0] + " (<strong>id: " + row[1] + "</strong>)";
	}
	
	function formatResult(row) {
		return row[0].replace(/(<.+?>)/gi, '');
	}

	$("#edit0__add4").autocomplete("tags_array.php", {
		selectFirst: false,
		multiple: true,
		mustMatch: false,
		matchContains: false,
		autoFill: false
	});
	
	$("#edit0_4").bind("click", function(){
		$("#edit0_4").autocomplete("tags_array.php", {
			selectFirst: false,
			multiple: true,
			mustMatch: false,
			matchContains: false,
			autoFill: false
		});
	});
});
</script>
FIN;
		return $strJS;
	}
}	
?>