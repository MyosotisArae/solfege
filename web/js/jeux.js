function selectGame(reference)
{
	caseNom = document.getElementById("jeuNo"+reference+"_nom");
	caseBut = document.getElementById("jeuNo"+reference+"_but");
	casePpe = document.getElementById("jeuNo"+reference+"_ppe");
	caseImg = document.getElementById("jeuNo"+reference+"_img");
	caseDur = document.getElementById("jeuNo"+reference+"_dur");
	caseNbj = document.getElementById("jeuNo"+reference+"_nbj");
	caseNiv = document.getElementById("jeuNo"+reference+"_niv");
	// Description de ce niveau
	texteNiveau = "";
	niveau = caseNiv.innerHTML;
	if (niveau == 1) { texteNiveau = "Jeu tout public, assimilable m&ecirc;me par les plus jeunes"; }
	else if (niveau == 2) { texteNiveau = "Jeu simple, mais qui requiert un peu de r&eacute;flexion"; }
	else if (niveau == 3) { texteNiveau = "Jeu abordable, mais qui exige de la concentration"; }
	else if (niveau == 4) { texteNiveau = "Jeu strat&eacute;gique avec des r&egrave;gles plut&ocirc;t cons&eacute;quentes"; }
	else if (niveau == 5) { texteNiveau = "Ce jeu peut &ecirc;tre difficile &agrave; ma&icirc;triser"; }

	document.getElementById("jeu_nom").innerHTML = caseNom.innerHTML;
	document.getElementById("jeu_but").innerHTML = caseBut.innerHTML;
	document.getElementById("jeu_ppe").innerHTML = casePpe.innerHTML;
	document.getElementById("jeu_img").src = "imagesJeux/"+caseImg.innerHTML+".jpg";
	document.getElementById("jeu_dur").innerHTML = caseDur.innerHTML;
	document.getElementById("jeu_nbj").innerHTML = caseNbj.innerHTML + '<br><img class="picto" id="jeu_niv" src="images/pictoNiv'+niveau+'.gif" title="'+texteNiveau+'">';
}