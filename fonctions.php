<?php

 function obtenir_recherche_par_nom($valeur) // fonction qui renvoie un tableau de tous les adherents
 {
	require 'config.php'; // pour connexion au SGBD
	
	$les_adherents = array(); // création du tableau
	$requete="select * from products where brand_name like '%$valeur%' or product_name like '%$valeur%'";
	$resultat_sql = mysqli_query($lien_base, "$requete");
	if($resultat_sql == false) // si impossible d'exécuter la requête SELECT
	{	
		die("Impossible d'executer la requete: $requete " . mysqli_error($lien_base));	
	}
	else // SELECT réussi
	{
		$nb_lignes=mysqli_affected_rows($lien_base); // compte le nombre de lignes du SELECT
		$i=1; // compteur
		while($i<=$nb_lignes)
		{
			// ajout des résultats du select
			$les_adherents[] = mysqli_fetch_array($resultat_sql); 
			$i=$i+1; // incrémentation
		}
		
	}

	return $les_adherents;// le tableau sera vide en cas d'erreur
}// fin fonction()
   ?>