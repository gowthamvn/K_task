<html>
<body>
    <form action="generate.php" method="post" >
    Input format must be KQYYXXXX
    <br>
    YY is year
    <BR>
    XXXX is unique id 
    <br><br>
        Starting value : 
        <input name="data" value="" pattern="[K][Q][0-9]{6}" />
        Ending value :
        <input name="end_data" value="" pattern="[K][Q][0-9]{6}" />
        <input type="submit" value="Generate qr">
    </form>
</body>
</html>
    
