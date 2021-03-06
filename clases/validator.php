<?php

require_once("db.php");
require_once("usuario.php");

class Validator {
  public function validarInformacion(db $db) {
		$arrayDeErrores = [];

		$nombreArchivo = $_FILES["avatar"]["name"];

		$extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

		if ($_FILES["avatar"]["error"] != UPLOAD_ERR_OK) {
			$arrayDeErrores["avatar"] = "Ey, no se subio bien la foto";
		}
		else if ($extension != "jpg" && $extension != "jpeg" && $extension != "gif" && $extension != "png") {
			$arrayDeErrores["avatar"] = "La foto no tiene un formato valido";
		}

		if ($_POST["nombre"] == "") {
			$arrayDeErrores["nombre"] = "Che, el nombre esta mal";
		}

		if ($_POST["email"] == "") {
			$arrayDeErrores["email"] = "Che, el mail no esta";
		}
		else if(filter_var($_POST["email"],  FILTER_VALIDATE_EMAIL) == false) {
			$arrayDeErrores["email"] = "Che, el FORMATO del mail esta mal";
		}
		else if ($db->traerPorEmail($_POST["email"]) != null) {
			$arrayDeErrores["email"] = "El mail esta repetido";
		}

		if (is_numeric($_POST["edad"]) == false) {
			$arrayDeErrores["edad"] = "Che, pusiste cualquier cosa en la edad";
		}

		if (strlen($_POST["password"]) < 6) {
			$arrayDeErrores["password"] = "Che, pone al menos 6 caracters de pass";
		}
		else if ($_POST["password"] != $_POST["cpassword"]) {
			$arrayDeErrores["password"] = "Las contraseñas no verifican";
		}

		if (isset($_POST["terminos"]) == false) {
			$arrayDeErrores["terminos"] = "Ey! Acepta los terminos";
		}

		return $arrayDeErrores;
	}

  public function validarLogin(db $db) {
		$arrayDeErrores = [];


		if ($_POST["email"] == "") {
			$arrayDeErrores["email"] = "Che, el mail no esta";
		}
		else if(filter_var($_POST["email"],  FILTER_VALIDATE_EMAIL) == false) {
			$arrayDeErrores["email"] = "Che, el FORMATO del mail esta mal";
		}
		else if ($db->traerPorEmail($_POST["email"]) == null) {
			$arrayDeErrores["email"] = "El mail no esta en la base";
		}
		else {
			//El mail existe!!
			$usuario = $db->traerPorEmail($_POST["email"]);
      var_dump(password_verify($_POST["password"], $usuario->getPassword()), $usuario->getPassword());

			if (password_verify($_POST["password"], $usuario->getPassword()) == false) {
				$arrayDeErrores["password"] = "La contraseña no verifica";
			}
		}

		return $arrayDeErrores;
	}

  public function validarEdicion(db $db, Usuario $usuario) {
		$arrayDeErrores = [];

		if ($_POST["nombre"] == "") {
			$arrayDeErrores["nombre"] = "Che, el nombre esta mal";
		}

		if ($_POST["email"] == "") {
			$arrayDeErrores["email"] = "Che, el mail no esta";
		}
		else if(filter_var($_POST["email"],  FILTER_VALIDATE_EMAIL) == false) {
			$arrayDeErrores["email"] = "Che, el FORMATO del mail esta mal";
		}
		else if ($db->traerPorEmail($_POST["email"]) != null && $_POST["email"] != $usuario->getEmail()) {
			$arrayDeErrores["email"] = "El mail esta repetido";
		}

		if (is_numeric($_POST["edad"]) == false) {
			$arrayDeErrores["edad"] = "Che, pusiste cualquier cosa en la edad";
		}

    if (password_verify($_POST["oldpassword"], $usuario->getPassword()) == false ){
      $arrayDeErrores["oldpassword"] = "Che, tu contraseña anterior esta mal";
    }

		if (strlen($_POST["password"]) < 6) {
			$arrayDeErrores["password"] = "Che, pone al menos 6 caracters de pass";
		}
		else if ($_POST["password"] != $_POST["cpassword"]) {
			$arrayDeErrores["password"] = "Las contraseñas no verifican";
		}

		return $arrayDeErrores;
	}
}

?>
