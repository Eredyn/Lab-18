<?php
// start session
session_start();
// initialize session shopping cart
if (!isset($_SESSION['cart']))
{
$_SESSION['cart'] = array();
}
// initialize session shopping cart
if (!isset($_SESSION['cart']))
{
$_SESSION['cart'] = array();
}
// look for catalog file
$catalogFile = "catalog.dat";
// file is available, extract data from it
// place into $CATALOG array, with SKU as key
if (file_exists($catalogFile))
{
$data = file($catalogFile);
foreach ($data as $line)
{
$lineArray = explode(':', $line);
$sku = trim($lineArray[0]);
$CATALOG[$sku]['desc'] = trim($lineArray[1]);
$CATALOG[$sku]['price'] = trim($lineArray[2]);
}
}
// file is not available
// stop immediately with an error
else
{
die("Could not find catalog file");
}
// check to see if the form has been submitted
// and which submit button was clicked
// if this is an add operation
// add to already existing quantities in shopping cart
if ($_POST['add'])
{
foreach ($_POST['a_qty'] as $k => $v)
{
// if the value is 0 or negative
// don't bother changing the cart
if ($v > 0)
{
$_SESSION['cart'][$k] = $_SESSION['cart'][$k] + $v;
}
}
}
// if this is an update operation
// replace quantities in shopping cart with values entered
else if ($_POST['update'])
{
foreach ($_POST['u_qty'] as $k => $v)
{
// if the value is empty, 0 or negative
// don't bother changing the cart
if ($v != "" && $v >= 0)
{
$_SESSION['cart'][$k] = $v;
}
}
}
// if this is a clear operation
// reset the session and the cart
// destroy all session data
else if ($_POST['clear'])
{
$_SESSION = array();
session_destroy();
}
?>
<html>
<head></head>
<body>
<h2>Catalog</h2>
Please add items from the list below to your shopping cart.
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table border="0" cellspacing="10">
<?php
// print items from the catalog for selection
foreach ($CATALOG as $k => $v)
{
echo "<tr><td colspan=2>";
echo "<b>" . $v['desc'] . "</b>";
echo "</td></tr>\n";
echo "<tr><td>";
echo "Price per unit: " . $CATALOG[$k]['price'];
echo "</td><td>Quantity: ";
echo "<input size=4 type=text name=\"a_qty[" . $k . "]\">";
echo "</td></tr>\n";
}
?>
<tr>
<td colspan="2">
<input type="submit" name="add" value="Add items to cart">
</td>
</tr>
</table>
<hr />
<hr />
<h2>Shopping cart</h2>
<table width="100%" border="0" cellspacing="10">
<?php
// initialize a variable to hold total cost
$total = 0;
// check the shopping cart
// if it contains values
// look up the SKUs in the $CATALOG array
// get the cost and calculate subtotals and totals
if (is_array($_SESSION['cart']))
{
foreach ($_SESSION['cart'] as $k => $v)
{
// only display items that have been selected
// that is, quantities > 0
if ($v > 0)
{
$subtotal = $v * $CATALOG[$k]['price'];
$total += $subtotal;
echo "<tr><td>";
echo "<b>$v unit(s) of " . $CATALOG[$k]['desc'] ↵
. "</b>";
echo "</td><td>";
echo "New quantity: <input size=4 type=text ↵
name=\"u_qty[" . $k . "]\">";
echo "</td></tr>\n";
echo "<tr><td>";
echo "Price per unit: " . $CATALOG[$k]['price'];
echo "</td><td>";
echo "Sub-total: " . sprintf("%0.2f", $subtotal);
echo "</td></tr>\n";
}
}
}
?>
<tr>
<td><b>TOTAL</b></td>
<td><b><?=sprintf("%0.2f", $total)?></b></td>
</tr>
<tr>
<td><input type="submit" name="update" value="Update Cart"></td>
<td><input type="submit" name="clear" value="Clear Cart"></td>
</tr>
</table>
</form>
</body>
</html>
