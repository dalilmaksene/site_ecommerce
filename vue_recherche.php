<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Mon annuaire en PHP</title>
 <meta NAME="Author" CONTENT="Sami Youssoufi">
 <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
  <!-- appel feuille de style -->
 <link href="style_op.css" type="text/css" rel="stylesheet" media="all">
</head>
<body>
<h1>Rechercher un nom</h1>
<table>
<form method='POST' action='vue_cherche_par_nom.php' name='annuaire' enctype='application/x-www-form-urlencoded'>
<tr>
	<td>Nom cherché : </td>
	<td><input type='text' name='brand_name' size='20'></td>
</tr>
<tr>
<td colspan="2"><input type='submit' value='Rechercher'></td>
</tr>
</form>
</table>

</body>
</html>