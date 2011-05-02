<?
class Carrusel extends MModel {
	function Select(){
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
					<td><input type="hidden" name="p" value="top_carrusel"/>
						Canal			
					</td>
					<td>
						<select name="category" onchange="document.frmCat.submit();">';
		
		$lngIdCategoria = ctype_digit($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : 0;
		$strTopCarrusel .='		<option value="0"  ';
		$strTopCarrusel .= $row['id']==0 ? "selected" : "";
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
}	
?>