<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
<?

	$inst = "1045341";
	$authPW = "5A9W9cu";
	// a ref to a current future pay id
	$fpid = "20024134";


?>


<form action="https://secure-test.worldpay.com/wcc/purchase" method="post">
	<input type="hidden" name="instId" value="<? echo $inst ?>">
	<input type="hidden" name="testMode" value="100">
	<input type="hidden" name="currency" value="GBP">
	<input type="hidden" name="cartId" value="MerchantReference">
	<input type="hidden" name="option" value="0">
	<input type="hidden" name="futurePayType" value="limited">
	<input type="submit" value="Make Purchase">
</form>

</body>
</html>