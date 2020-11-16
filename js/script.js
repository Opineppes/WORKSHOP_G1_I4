var ancienneValeurs = { //map pour stocker les ancienne valeur du profil et les reafficher corectement
		nom: "",
		prenom: "",
		promo: "",
		campus: ""
};

var baseWebPath = "/";

$("#btn-inscription").click(function(e) {
	$("#inscription-modal").modal("show");
	$("#connexion-modal").modal("hide");
});

$(".avatar").click(function(e) {
	$("#avatar").get(0).click();
});

function onClickLinkError(e) {
	console.log("e");
	$("#modal-error").modal("hide");
}

$("#modifprofil").click(onClicModifProfil);

function onClicModifCancel(e) {
	$("#control-modifprofil").html(
		"<button type=\"button\" class=\"btn btn-outline-info w-100\" data-toggle=\"modal\" data-target=\"#modifpasswd\">Modifier le mot de passe</button>"+
		"<button type=\"button\" class=\"btn btn-outline-dark w-100\" id=\"modifprofil\">Modifier le profil</button>"
	);
	
	$("#profil-Nom").html(ancienneValeurs.nom);
	$("#profil-Prenom").html(ancienneValeurs.prenom);
	$("#profil-Promo").html(ancienneValeurs.promo);
	$("#profil-Campus").html(ancienneValeurs.campus);
	
	$("#modifprofil").click(onClicModifProfil);
}

function onClicModifProfil(e) {
	$("#control-modifprofil").html(
		"<button type=\"button\" class=\"btn btn-outline-dark w-100\" id=\"button-cancel\">Annuler</button>"+
		"<button type=\"submit\" class=\"btn btn-outline-info w-100\"> Sauvegarder </button>"
	);

	ancienneValeurs.nom = $("#profil-Nom").html();
	ancienneValeurs.prenom = $("#profil-Prenom").html();
	ancienneValeurs.promo = $("#profil-Promo").html();
	ancienneValeurs.campus = $("#profil-Campus").html();
	
	$("#profil-Nom").html("<input type=\"text\" class=\"form-control\" value=\"" + ancienneValeurs.nom + "\" />");
	$("#profil-Prenom").html("<input type=\"text\" class=\"form-control\" value=\"" + ancienneValeurs.prenom + "\" />");
	$("#profil-Promo").html(
		"<select class=\"form-control\">"+
		"	<option value=\"B1\">B1</option>"+
		"	<option value=\"B2\">B2</option>"+
		"	<option value=\"B3\">B3</option>"+
		"	<option value=\"I5\">I4</option>"+
		"	<option value=\"I4\">I5</option>"+
		"</select>"
	);
	$("#profil-Campus").html(
		"<select class=\"form-control\">"+
		"	<option value=\"Arras\">Arras</option>"+
		"	<option value=\"Lille\">Lille</option>"+
		"</select>"
	);
	
	$("#profil-Promo select").val(ancienneValeurs.promo);
	$("#profil-Campus select").val(ancienneValeurs.campus);
	
	
	$("#button-cancel").click(onClicModifCancel);
}

$("#deconnexion").click(function(e) {
	e.preventDefault();
	
	$.post( {
		url: baseWebPath,
		data: {
			protocole: "deconnexion"
		},
		dataType: "json"
	} ).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location = baseWebPath;
			}
		}
	});
});

$("#connexion-form").submit(function(e) {
	e.preventDefault();
	
	$.post( {
		url: baseWebPath,
		data: {
			protocole: "connexion",
			email: $("#email-connexion").val(),
			mot_de_passe: $("#mot-de-passe-connexion").val()
		},
		dataType: "json"
	} ).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location = baseWebPath;
			} else {
				$("#connexion-modal").modal("show");
				$("#error-connexion").remove();
				$("#connexion-body").prepend(
					"<div class=\"alert alert-danger\" role=\"alert\" id=\"error-connexion\">"+
					"<strong>Nope !</strong> &nbsp;&nbsp;"+ res.error +
					"</div>"
				)
			}
		}
	});
});

$("#inscription-form").submit(function(e) {
	e.preventDefault();

	
	$.post( {
		url: baseWebPath,
		data: {
			protocole: "inscription",
			email: $("#email").val(),
			mot_de_passe: $("#mot-de-passe").val(),
			verif_mot_de_passe: $("#valide-mot-de-passe").val(),
			prenom: $("#prenom").val(),
			nom: $("#nom").val(),
			annee: $("#annee").val(),
			campus: $("#campus").val()
		},
		dataType: "json"
	} ).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location = baseWebPath;
			} else {
				$("#inscription-modal").modal("show");
				$("#error-inscription").remove();
				$("#inscription-body").prepend(
					"<div class=\"alert alert-danger\" role=\"alert\" id=\"error-inscription\">"+
					"<strong>Nope !</strong> &nbsp;&nbsp;"+ res.error +
					"</div>"
				)
			}
		}
	});
});

$("#avatar").on('change', function(e) {
	var formData = new FormData($("#form-avatar").get(0));
	console.log(formData);
	
	$.post({
		url: baseWebPath,
		data: formData,
		dataType: 'json',
		processData: false,
		contentType: false,
	}).done(function(res){
		if(res.result != undefined) {
			if(res.result) {
				document.location.reload();
			} else {
				$("#error-message").html(res.error);
				$(".link-error").click(onClickLinkError);
				$("#modal-error").modal("show");
			}
		}
	});
});

$("#modifprofil-form").submit(function(e){
	e.preventDefault();
	
	$.post({
		url: baseWebPath,
		data: {
			protocole: "modif-profil",
			email: $("#profil-Email").html(),
			nom: $("#profil-Nom input").val(),
			prenom: $("#profil-Prenom input").val(), 
			promo: $("#profil-Promo select").val(),
			campus: $("#profil-Campus select").val()
		},
		dataType: "json"
	}).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location.reload();
			} else {
				$("#error-message").html(res.error);
				$(".link-error").click(onClickLinkError);
				$("#modal-error").modal("show");
			}
		}
	});
});

$("#modifpasswd-form").submit(function(e){
	e.preventDefault();

	$.post({
		url: "/",
		data: {
			protocole: "modif-passwd",
			passwd: $("#modif-passwd").val(),
			confpasswd: $("#modif-confpasswd").val(),
			lastpasswd: $("#modif-lastpasswd").val(),
			email: $("#profil-Email").html()
		},
		dataType: "json"
	}).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location.reload();
			} else {
				$("#error-message").html(res.error);
				$(".link-error").click(onClickLinkError);
				$("#modal-error").modal("show");
			}
		}
	});
});

$("#article-form").submit(function(e) {
	e.preventDefault();
	
	var infos_article = {};
	
	var infos = $("#article-infos .input-group");
	for(info of infos.toArray()) {
		var id = info.id;
		var value = null;
		
		var args = id.split("_");
		if(args[0] == "H") {
			var h = $("#" + id + " .h").val();
			var m = $("#" + id + " .m").val();
			value = h + ":" + m;
		} else if(args[0] == "D"){
			var date_iso = new Date($("#" + id + " input").val());
			value = date_iso.getDate() + "/" + (date_iso.getMonth() + 1) + "/" + date_iso.getFullYear();
		} else {
			value = $("#" + id + " input").val();
		}
		
		infos_article[id] = value;
	}
	
	$.post({
		url: "/",
		data: {
			protocole: "ajout-article",
			titre: $("#titre").val(),
			description: $("#description").val(),
			infos: infos_article,
			rubrique: $("#article-rubrique").val()
		},
		dataType: "json"
	}).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location.reload();
			} else {
				$("#error-message").html(res.error);
				$(".link-error").click(onClickLinkError);
				$("#modal-error").modal("show");
			}
		}
	});
})

$("#form-commentaire").submit(function(e) {
	e.preventDefault();
	
	$.post({
		url: "/",
		data: {
			protocole: "ajout-commentaire",
			commentaire: $("#commentaire").val(),
			article: $("#article").val()
		},
		dataType: "json"
	}).done(function(res) {
		if(res.result != undefined) {
			if(res.result) {
				document.location.reload();
			} else {
				$("#error-message").html(res.error);
				$(".link-error").click(onClickLinkError);
				$("#modal-error").modal("show");
			}
		}
	});
});