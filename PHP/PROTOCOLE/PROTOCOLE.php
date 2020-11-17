<?php

	class PROTOCOLE {
		
		public static function chooser() {
			global $user;
			
			if(isset($_POST["protocole"])) {
				$protocole = $_POST["protocole"];
				
				if($protocole == "connexion") {
					return PROTOCOLE::proto_connexion();
				} else if($protocole == "inscription") {
					return PROTOCOLE::proto_inscription();
				} else if($protocole == "deconnexion") {
					return PROTOCOLE::proto_deconnexion();
				} else if($user != null){
					if($protocole == "modif-avatar") {
						return PROTOCOLE::proto_modif_image();
					} else if($protocole == "modif-profil") {
						return PROTOCOLE::proto_modif_profil();
					} else if($protocole == "modif-passwd") {
						return PROTOCOLE::proto_modif_passwd();
					} else if($protocole == "ajout-article") {
						return PROTOCOLE::proto_ajout_article();
					} else if($protocole == "ajout-commentaire") {
						return PROTOCOLE::proto_ajout_commentaire();
					}
				}
			}
		}
		
		public static function proto_deconnexion() {
			session_destroy();
			
			return "{\"result\": true}";
		}
		
		//protocoles
		public static function proto_connexion() {
			global $table_utilisateur;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("email", "mot_de_passe"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("email", "mot_de_passe"))) {
					
					if($table_utilisateur->valid(array("email"=>$_POST["email"], "mdp"=>md5($_POST['mot_de_passe'])))) {
						$_SESSION['connect'] = true;
						$_SESSION['email'] = $_POST["email"];
						
						return "{\"result\": true}";
					} else {
						return "{\"result\": false, \"error\": \"Identifiant ou mot de passe incorect.\"}";
					}
					
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_inscription() {
			global $table_utilisateur;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("email", "mot_de_passe", "verif_mot_de_passe", "nom", "prenom", "annee", "campus"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("email", "mot_de_passe", "verif_mot_de_passe", "nom", "prenom", "annee", "campus"))) {
					
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
						
						if(!$table_utilisateur->exist(array("email"=>$_POST["email"]))) {
							
							if(md5($_POST['mot_de_passe']) == md5($_POST['verif_mot_de_passe']) ) {
								
								$table_utilisateur->create(array(
									":email" => $_POST["email"],
									":mdp" => md5($_POST["mot_de_passe"]),
									":nom" => $_POST["nom"],
									":prenom" => $_POST["prenom"],
									":annee" => $_POST["annee"],
									":campus"=> $_POST["campus"]
								));
								
								$_SESSION['connect'] = true;
								$_SESSION['email'] = $_POST["email"];
								
								return "{\"result\": true}";
								
							} else {
								return "{\"result\": false, \"error\": \"Les deux mot deux passe ne sont pas identiques.\"}";
							}
						} else {
							return "{\"result\": false, \"error\": \"Email déjà utilisée.\"}";
						}
					} else {
						return "{\"result\": false, \"error\": \"Veuillez saisir une adresse mail valide.\"}";
					}
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_modif_image() {
			global $table_utilisateur;
			
			global $user;
			
			//avatar: image, email: identifiant
			if( isset($_FILES['avatar']) and isset($_POST['email']) and !empty($_POST['email']) ) {
				$extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
				$taille_max = 5000000;
				$dest_dossier = 'img/profils/';
							
				$nomFile = explode('.', $_FILES['avatar']['name']);
				$extension = $nomFile[count($nomFile)-1];
				if( in_array( $extension, $extensions_ok ) )
				{
					if( file_exists($_FILES['avatar']['tmp_name']) && (filesize($_FILES['avatar']['tmp_name']))> $taille_max){
						return "{\"result\": false, \"error\": \"Votre fichier doit faire moins de 500ko.\"}";
					}
					else
					{
						if($user['email'] == $_POST['email'] or $user['admin'] == "1") {
							
							$dest_fichier = basename($_POST['email']);
							$dest_fichier = strtr($dest_fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy_');
							$dest_fichier = str_replace(".", "_", $dest_fichier);
							$dest_fichier = preg_replace('/([^.a-z0-9]+)/i', '_', $dest_fichier);
							
							move_uploaded_file($_FILES['avatar']['tmp_name'], $dest_dossier . $dest_fichier . '.' . $extension);
							$avatar = $dest_dossier . $dest_fichier . '.' . $extension;
							$table_utilisateur->update_image(array("email"=>$_POST['email'], "image"=>$avatar));
							
							return "{\"result\": true}";
						}
					}
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_modif_profil() {
			global $table_utilisateur;
				
			global $user;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("nom", "prenom", "email", "promo", "campus"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("nom", "prenom", "email", "promo", "campus"))) {
					
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
						
						if($user['email'] == $_POST['email'] or $user['admin'] == "1") {
							
							if($table_utilisateur->exist(array("email"=>$_POST["email"]))) {

								$table_utilisateur->update(
									array(
										":email"=>$_POST['email'],
										":nom"=>$_POST['nom'],
										":prenom"=>$_POST['prenom'],
										":annee"=>$_POST['promo'],
										":campus"=>$_POST['campus']
									)
								);
								
								return "{\"result\": true}";
							}
						}
						
					} else {
						return "{\"result\": false, \"error\": \"Veuillez saisir une adresse mail valide.\"}";
					}
					
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_modif_passwd() {
			global $table_utilisateur;
			
			global $user;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("passwd", "confpasswd", "email"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("passwd", "confpasswd", "email"))) {

					$passwd = md5($_POST['passwd']);
					$confpasswd = md5($_POST['confpasswd']);
					
					if($passwd == $confpasswd) 
					{
						if($user['admin'] == "0" and isset($_POST['lastpasswd']) and $user['email'] == $_POST['email']) {
							if($table_utilisateur->valid(array("email"=>$_POST['email'], "mdp"=>md5($_POST['lastpasswd'])))) {
								$table_utilisateur->update_passwd(array("email"=>$_POST['email'], "mdp"=>$passwd ));
								return "{\"result\": true}";
							} else {
								return "{\"result\": false, \"error\": \"L'ancien mot de passe n'est pas correct.\"}";
							}
						} else if($user['admin'] == "1") {
							$table_utilisateur->update_passwd(array("email"=>$_POST['email'], "mdp"=>$passwd ));
							return "{\"result\": true}";
						} else {
							return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
						}
					} else {
						return "{\"result\": false, \"error\": \"Les deux mots de passe ne correspondent pas.\"}";
					}
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_ajout_article() {
			global $table_article;
			global $table_rubrique;
			global $user;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("titre", "description", "infos", "rubrique"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("titre", "description", "infos", "rubrique"))) {
					$rubrique = $table_rubrique->selectOne(array("nomRubrique"=>$_POST['rubrique']));
					
					if($rubrique != false) {
						date_default_timezone_set("Europe/Paris");

						$table_article->create(array(
							"dateCreation"=>strftime("%Y-%m-%d %H:%M:%S"),
							"infos"=>json_encode($_POST['infos']),
							"emailUtilisateur"=>$user['email'],
							"nomRubrique"=>$_POST['rubrique'],
							"titre"=>$_POST['titre'],
							"description"=>$_POST['description']
						));
						
						return "{\"result\": true}";
					} else {
						return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
					}
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
		
		public static function proto_ajout_commentaire() {
			global $table_article;
			global $table_commentaire;
			global $user;
			
			if(PROTOCOLE::verif_presence_args($_POST, array("commentaire", "article"))) {
				if(PROTOCOLE::verif_empty_args($_POST, array("commentaire", "article"))) {
					$article = $table_article->selectOne(array("id"=>$_POST['article']));
					
					if($article != false) {
						date_default_timezone_set("Europe/Paris");

						$table_commentaire->create(array(
							"dateHeure"=>strftime("%Y-%m-%d %H:%M:%S"),
							"emailUtilisateur"=>$user['email'],
							"contenu"=>$_POST['commentaire'],
							"idArticle"=>$_POST['article']
						));
						
						return "{\"result\": true}";
					} else {
						return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
					}
				} else {
					return "{\"result\": false, \"error\": \"Certains champs du formulaire sont vide\"}";
				}
			} else {
				return "{\"result\": false, \"error\": \"Une erreur s'est produite.\"}";
			}
		}
	
		//outils
		public static function verif_presence_args($to_verif, $args_need) {
			$res = true;
			foreach($args_need as $id=>$arg_need) {
				if(!isset($to_verif[$arg_need])) {
					$res = false;
				}
			}
			return $res;
		}
		
		public static function verif_empty_args($to_verif, $args_need) {
			$res = true;
			foreach($args_need as $id=>$arg_need) {
				if(empty($to_verif[$arg_need])) {
					$res = false;
				}
			}
			return $res;
		}
	}

?>