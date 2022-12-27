<?php 

require("items.class.php");

class Sistema extends Items
{
	public function createRespalBD()
	{
		$fecha = date("Ymd");

		$bd = "sistema_bustamante";

		$out_sql = "".$bd."_".$fecha.".sql";

		$dump = 'c:\xampp\mysql\bin\mysqldump --user="root" --password="" '.$bd.' > '.$out_sql;

		system($dump,$output);

		$zip = new ZipArchive();

		$out_zip_sql = "".$bd."_".$fecha.".zip";

		if ($zip->open($out_zip_sql, ZIPARCHIVE::CREATE) == true) {
			$zip->addFile($out_sql);
			$zip->close();
			unlink($out_sql);
			header("Location: $out_zip_sql");
		}

	}
	public function deleteZipBackup()
	{
		$fecha = date("Ymd");
		$bd = "sistema_bustamante";
		$out_zip_sql = "".$bd."_".$fecha.".zip";
		unlink($out_zip_sql);

		$sistema = new Sistema;
		$con = $sistema->conexion();

		$id_usuario = $_SESSION['id_usuario'];

		$insert_export_db = $con->query("INSERT INTO `export_db`(`id_export`, `id_usuario`, `fecha`) VALUES (NULL,'$id_usuario',CURRENT_TIMESTAMP)");
		
	}
}

?>