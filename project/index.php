<?php

    session_start();
    include "mysql.php";

    if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
	{
        $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
        $userLogged = mysql_fetch_array($query);

        $userPanel = '
            <div class="col-md-offset-11" style="margin-top: 11px;">
                <div class="dropdown" style="margin-top: -3px;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="'.$userLogged[avatar].'" class="userAvatar"><span class="caret" style="font-size: 18px; color: #FFF;"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" style="margin-left: -25px;">
                        <li><a href="index.php?action=editprofile">Edytuj dane</a></li>
                        <li><a href="index.php?action=changeavatar">Zmień avatar</a></li>
                        <li class="divider"></li>
                        <li><a href="index.php?action=newauction">Dodaj aukcję</a></li>
                        <li><a href="#">Moje aukcje</a></li>
                        <li><a href="#">Historia zakupów</a></li>
                        <li class="divider"></li>
                        <li><a href="index.php?action=logout">Wyloguj</a></li>
                    </ul>
                </div>
            </div>
        ';
    }
    else
    {
        $userPanel = '
            <div class="col-md-offset-11" style="margin-top: 23px;">
                <div class="dropdown" style="margin-top: -3px;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-decoration: none;">
                        <span class="myAccount">Moje konto</span><span class="caret" style="font-size: 18px; color: #FFF;"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu" style="margin-left: -25px;">
                        <li><a href="index.php?action=login">Zaloguj się</a></li>
                        <li><a href="index.php?action=register">Zarejestruj się</a></li>
                    </ul>
                </div>
            </div>
        ';
    }

    if($_GET['action'] === "login")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            header("Refresh:0; url=index.php");
            echo "<script language='javascript'>alert('Jesteś już zalogowany!');</script>";
            return 0;
        }

        if(isset($_POST['loginSubmit']))
        {
            $username = $_POST['loginName'];
            $password = $_POST['loginPassword'];

            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$username."' AND password LIKE '".$password."'");
            //$userLogged = mysql_fetch_array($query);
            
            if(mysql_num_rows($query) > 0)
            {
                $_SESSION['password'] = $password;
                $_SESSION['username'] = $username;
                header("Refresh:0; url=index.php");
            }
            else
            {
                header("Refresh:0; url=index.php?action=login");
                echo "<script language='javascript'>alert('Podałeś błędne dane!');</script>";
            }
        }

        $index = '
            <div class="container" style="margin-top: 20px;">
                <div class="row">
                    <div class="backgroundBox">
                        <form class="form-horizontal" action="" method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" class="form-control input-lg" id="userName" name="loginName" placeholder="Nazwa użytkownika" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="password" class="form-control input-lg" id="userPassword" name="loginPassword" placeholder="Hasło" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <button type="submit" name="loginSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Zaloguj!</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        ';
    }
    else if($_GET['action'] === "register")
    {
        if(isset($_POST['registerSubmit']))
        {
            $username = $_POST['registerName'];
            $password = $_POST['registerPassword'];
            $firstname = $_POST['registerFirstname'];
            $surname = $_POST['registerSurname'];
            $email = $_POST['registerEmail'];
            $place = $_POST['registerPlace'];
            $phone = $_POST['registerPhone'];
            $bank = $_POST['registerBank'];

            $query = mysql_query("SELECT NULL FROM users WHERE username LIKE '".$username."'");
            $query2 = mysql_query("SELECT NULL FROM users WHERE email LIKE '".$email."'");

            if(mysql_num_rows($query) > 0)
            {
                echo "<script language='javascript'>alert('Taka nazwa użytkownika jest zajęta - wybierz inną!');</script>";
            }
            else if(mysql_num_rows($query2) > 0)
            {
                echo "<script language='javascript'>alert('Taki adres e-mail jest już zarejestrowany - wybierz inny!');</script>";
            }
            else if(strlen($username) > 32 || strlen($username) < 3)
            {
                echo "<script language='javascript'>alert('Nazwa użytkownika powinna zawierać 3 - 32 znaków!');</script>";
            }
            else if(strlen($password) > 32 || strlen($password) < 3)
            {
                echo "<script language='javascript'>alert('Hasło powinno zawierać 3 - 32 znaków!');</script>";
            }
            else if(strlen($firstname) > 64 || strlen($firstname) < 3)
            {
                echo "<script language='javascript'>alert('Imię powinno zawierać 3 - 64 znaków!');</script>";
            }
            else if(strlen($surname) > 64 || strlen($surname) < 3)
            {
                echo "<script language='javascript'>alert('Nazwisko powinno zawierać 3 - 64 znaków!');</script>";
            }
            else if(strlen($place) > 128 || strlen($place) < 3)
            {
                echo "<script language='javascript'>alert('Miejscowość powinna zawierać 3 - 128 znaków!');</script>";
            }
            else if(strlen($phone) != 9)
            {
                echo "<script language='javascript'>alert('Numer telefonu składa się z 9 cyfr!');</script>";
            }
            else if(!is_numeric($phone))
            {
                echo "<script language='javascript'>alert('Numer telefonu składa się z samych cyfr!');</script>";
            }
            else if(strlen($bank) != 26)
            {
                echo "<script language='javascript'>alert('Numer konta bankowego składa się z 26 cyfr!');</script>";
            }
            else if(!is_numeric($bank))
            {
                echo "<script language='javascript'>alert('Numer konta bankowego składa się z samych cyfr!');</script>";
            }
            else
            {
                mysql_query("INSERT INTO users (username, password, firstname, surname, email, phone, place, bank) VALUES ('".$username."', '".$password."', '".$firstname."', '".$surname."', '".$email."', '".$phone."', '".$place."', '".$bank."')");
                header("Refresh:0; url=index.php?action=login");
                echo "<script language='javascript'>alert('Pomyślnie założyłeś konto - możesz teraz się zalogować!');</script>";
            }
        }

        $index = '
            <div class="container" style="margin-top: 20px;">
                <div class="row">
                    <div class="backgroundBox">
                        <form class="form-horizontal" action="" method="post">
                            <div class="form-group">
                                <label for="userName" class="col-sm-2 control-label">Nazwa użytkownika</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userName" name="registerName" placeholder="Wpisz tutaj nazwę, która będzie twoim identyfikatorem" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userPassword" class="col-sm-2 control-label">Hasło</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input-lg" id="userPassword" name="registerPassword" placeholder="Wpisz tutaj hasło, które będzie zabezpieczać twoje konto" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userFirstname" class="col-sm-2 control-label">Imię</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userFirstname" name="registerFirstname" placeholder="Wpisz tutaj swoję imię" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userSurname" class="col-sm-2 control-label">Nazwisko</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userSurname" name="registerSurname" placeholder="Wpisz tutaj swoję nazwisko" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userMail" class="col-sm-2 control-label">E-mail</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control input-lg" id="userMail" name="registerEmail" placeholder="Wpisz tutaj swój e-mail" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userPlace" class="col-sm-2 control-label">Miejscowość</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userPlace" name="registerPlace" placeholder="Wpisz tutaj swoją miejscowość" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userPhone" class="col-sm-2 control-label">Telefon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userPhone" name="registerPhone" placeholder="Wpisz tutaj swój numer telefonu" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userBank" class="col-sm-2 control-label">Konto bankowe</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userBank" name="registerBank" placeholder="Wpisz tutaj swój numer konta bankowego" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" name="registerSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Zarejestruj!</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        ';
    }
    else if($_GET['action'] === "editprofile")
    {
        if(empty($_SESSION['username']) || empty($_SESSION['password']))
        {
            header("Refresh:0; url=index.php");
            echo "<script language='javascript'>alert('Musisz być zalogowany!');</script>";
            return 0;
        }

		$query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
        $userLogged = mysql_fetch_array($query);
		
		if(isset($_POST['editSubmit']))
        {
            $username = $_POST['editName'];
            $password = $_POST['editPassword'];
            $firstname = $_POST['editFirstname'];
            $surname = $_POST['editSurname'];
            $email = $_POST['editEmail'];
            $place = $_POST['editPlace'];
            $phone = $_POST['editPhone'];
            $bank = $_POST['editBank'];
			
			mysql_query("UPDATE users SET password = '".$password."', firstname = '".$firstname."', surname = '".$surname."', email = '".$email."', phone = '".$phone."', place = '".$place."', bank = '".$bank."' WHERE id = '".$userLogged['id']."'");
			header("Refresh:0; url=index.php?action=editprofile");
			echo "<script language='javascript'>alert('Pomyślnie zmieniłeś swoje dane');</script>";
		}
		
		$index = '
            <div class="container" style="margin-top: 20px;">
                <div class="row">
                    <div class="backgroundBox">
                        <form class="form-horizontal" action="" method="post">
                            <div class="form-group">
                                <label for="userPassword" class="col-sm-2 control-label">Hasło</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input-lg" id="userPassword" name="editPassword" value="'.$userLogged[password].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userFirstname" class="col-sm-2 control-label">Imię</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userFirstname" name="editFirstname" value="'.$userLogged[firstname].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userSurname" class="col-sm-2 control-label">Nazwisko</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userSurname" name="editSurname" value="'.$userLogged[surname].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userMail" class="col-sm-2 control-label">E-mail</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control input-lg" id="userMail" name="editEmail" value="'.$userLogged[email].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userPlace" class="col-sm-2 control-label">Miejscowość</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userPlace" name="editPlace" value="'.$userLogged[place].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userPhone" class="col-sm-2 control-label">Telefon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userPhone" name="editPhone" value="'.$userLogged[phone].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="userBank" class="col-sm-2 control-label">Konto bankowe</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" id="userBank" name="editBank" value="'.$userLogged[bank].'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" name="editSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Zapisz!</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        ';
    }
    else if($_GET['action'] === "newauction")
    {
        $query = mysql_query("SELECT * FROM categories");
        while ($row = mysql_fetch_array($query))
        {
            $auctionCategories .= '
                <option value="'.$row[id].'">'.$row[name].'</option>
            ';
        }

        $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
        $userLogged = mysql_fetch_array($query);
		
		if(isset($_POST['auctionSubmit']))
        {
            $name = $_POST['auctionName'];
            $category = $_POST['auctionCategory'];
            $description = $_POST['auctionDesc'];
            $buyPrize = $_POST['auctionBuy'];
            $biddingPrize = $_POST['auctionBid'];
            $image = $_POST['auctionImage'];

            if(empty($buyPrize) && empty($biddingPrize))
            {
                echo "<script language='javascript'>alert('Dwie ceny nie mogą być puste!');</script>";
            }
            else if($category == 0)
            {
                echo "<script language='javascript'>alert('Nie wybrałeś kategorii!');</script>";
            }
            else if(!empty($buyPrize) && (!is_numeric($buyPrize)))
            {
                echo "<script language='javascript'>alert('Cena kup teraz musi być liczbą!');</script>";
            }
            else if(!empty($buyBid) && (!is_numeric($buyBid)))
            {
                 echo "<script language='javascript'>alert('Cena licytacji musi być liczbą!');</script>";
            }
            else if(strlen($buyPrize) > 16 || strlen($biddingPrize) > 16)
            {
                echo "<script language='javascript'>alert('Cena może być maksymalnie 16 liczbowa!');</script>";
            }
            else if(strlen($name) > 128)
            {
                echo "<script language='javascript'>alert('Nazwa może mieć tylko 128 znaków!');</script>";
            }
            else if(strlen($description) > 128)
            {
                echo "<script language='javascript'>alert('Opis może mieć tylko 512 znaków!');</script>";
            }
            else if(strlen($image) > 256)
            {
                echo "<script language='javascript'>alert('Link do zdjęcia może mieć tylko 256 znaków!');</script>";
            }
            else
            {
                mysql_query("INSERT INTO auctions (name, owner, category, buy_prize, bidding_prize, description, image) VALUES ('".$name."', '".$userLogged[id]."', '".$category."', '".$buyPrize."', '".$biddingPrize."', '".$description."', '".$image."')");                
			    header("Refresh:0; url=index.php");
			    echo "<script language='javascript'>alert('Pomyślnie utworzyłeś nową aukcję!');</script>";
            }
        }

        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
                            <form class="form-horizontal" action="" method="post">
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" title="Uwzględnij w nazwie słowa kluczowe aby osoby chcące wejść na aukcję mogły łatwo je znaleźć.">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
                                        <input type="text" class="form-control input-lg align-middle" id="auctionName" name="auctionName" placeholder="Nazwa aukcji" required>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" title="Wybierz kategorie, do której należy wystawiany przez ciebie przedmiot.">
                                        <select class="form-control input-lg" name="auctionCategory">
                                            <option value="0">Wybierz kategorie</option>
                                            '.$auctionCategories.'
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" data-html="true" title="W polu obok powinieneś opisać szczegółowo przedmiot, który wystawiasz na aukcję aby potencjalnemu klientowi było łatwiej poznać sprzedawane dobro. Do polepszenia wyglądu opisu aukcji możesz użyć <u>HTML</u>.">
                                        <textarea rows="15" class="form-control input-lg col-sm-10" id="auctionDesc" name="auctionDesc" placeholder="Wpisz tutaj zachęcający opis aukcji" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" title="W polu obok wprowadź link do zdjęcia przedmiotu, który wystawiasz na aukcję.">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-camera"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionImage" name="auctionImage" placeholder="Zdjęcie"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" data-html="true" title="Jeśli chcesz aby twoja aukcja posiadała cene kup-teraz, w polu obok wpisz kwotę - w przeciwnym wypadku zostaw pole pustę. <b><u>CENA KUP TERAZ LUB LICYTACJI MUSI BYĆ WYPEŁNIONA</u></b>.">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionBuy" name="auctionBuy" placeholder="Cena Kup-Teraz"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-2"></div>
                                    <div class="input-group col-sm-10" data-toggle="tooltip" data-placement="left" data-html="true" title="Jeśli chcesz aby twoja aukcja posiadała cene licytacji, w polu obok wpisz kwotę - w przeciwnym wypadku zostaw pole pustę. <b><u>CENA KUP TERAZ LUB LICYTACJI MUSI BYĆ WYPEŁNIONA</u></b>.">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionBid" name="auctionBid" placeholder="Cena licytacji"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name="auctionSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Wystaw aukcję!</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            ';
        }
        else
        {
            header("Refresh:0; url=index.php");
            echo "<script language='javascript'>alert('Dodawanie aukcji jest tylko dla zalogowanych!');</script>";
        }
    }
    else if($_GET['action'] === "logout")
    {
        session_destroy();
        header("Refresh:0; url=index.php");
        //echo "<script language='javascript'>alert('Pomyślnie się wylogowałeś!');</script>";
    }
    else if($_GET['action'] === "search")
    {
        $searchItem = $_GET['item'];
        //echo $searchItem;
        if(empty($searchItem))
        {
            header("Refresh:0; url=index.php");
            echo "<script language='javascript'>alert('Błąd! Musisz coś wpisać w wyszukiwarkę!');</script>";
        }
        else
        {
            $searchItem = htmlspecialchars($searchItem);
            $query = mysql_query("SELECT * FROM auctions WHERE name LIKE '%".$searchItem."%'");

            while ($row = mysql_fetch_array($query))
            {
                $query2 = mysql_query("SELECT * FROM categories WHERE id = '".$row[category]."'");
                $cat = mysql_fetch_array($query2);
                
                if($row["buy_prize"] != 0)
                {
                    $buyPrize = '
                        <span style="font-size: 25px; color: #2F3E46;">'.$row["buy_prize"].' zł</span><br />
                        <div style="margin-top: -10px;">
                            <span style="font-size: 12px; color: #FF8C42;">KUP TERAZ</span>
                        </div>
                    ';
                }
    
                if($row["bidding_prize"] != 0)
                {
                    $biddingPrize = '
                        <span style="font-size: 25px; color: #2F3E46;">'.$row["bidding_prize"].' zł</span><br />
                        <div style="margin-top: -10px;">
                            <span style="font-size: 12px; color: #FF8C42;">LICYTACJA</span>
                        </div>
                    ';
                }

                $auctions .= '
                    <tr>
                        <td class="col-xs-2">
                            <a href="index.php?action=showauction&id='.$row[id].'"><img src="'.$row["image"].'" class="img-responsive" /></a>
                        </td>
                        <td class="col-xs-8">
                            <span style="font-size: 25px;"><a href="index.php?action=showauction&id='.$row[id].'"><b>'.$row["name"].'</b></a></span><br />
                            <span style="font-size: 14px;">('.$cat["name"].')</span>
                        </td>
                        <td class="col-xs-2">
                            '.$buyPrize.'
                            '.$biddingPrize.'
                        </td>
                    </tr>
                    <tr style="height: 60px;"></tr>
                ';
            }

            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="mainBox" style="margin-right: -15px; margin-left: -15px;">
                                <table>
                                    '.$auctions.'
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
    }
    else if($_GET['action'] === "editauction")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $auctionID = intval($_GET['id']);
            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);
            $query = mysql_query("SELECT * FROM auctions WHERE id = ".$auctionID." AND owner = ".$userLogged['id']."");
            $auctionInfo = mysql_fetch_array($query);

            if(empty($_GET['id']) || mysql_num_rows($query) == 0)
            {
                header("Refresh:0; url=index.php");
                echo "<script language='javascript'>alert('Błąd! Nie ma takiej aukcji lub nie ty ją dodałeś!');</script>";
            }

            $query = mysql_query("SELECT * FROM categories");
            while ($row = mysql_fetch_array($query))
            {
                if($row['id'] == $auctionInfo['category'])
                {
                    $auctionCategories .= '
                        <option value="'.$row[id].'" selected="selected">'.$row[name].'</option>
                    ';
                }
                else
                {
                    $auctionCategories .= '
                        <option value="'.$row[id].'">'.$row[name].'</option>
                    ';
                }
                
            }

            if(isset($_POST['auctionSubmit']))
            {
                $name = $_POST['auctionName'];
                $category = $_POST['auctionCategory'];
                $description = $_POST['auctionDesc'];
                $buyPrize = $_POST['auctionBuy'];
                $biddingPrize = $_POST['auctionBid'];
                $image = $_POST['auctionImage'];
    
                if(empty($buyPrize) && empty($biddingPrize))
                {
                    echo "<script language='javascript'>alert('Dwie ceny nie mogą być puste!');</script>";
                }
                else if($category == 0)
                {
                    echo "<script language='javascript'>alert('Nie wybrałeś kategorii!');</script>";
                }
                else if(!empty($buyPrize) && (!is_numeric($buyPrize)))
                {
                    echo "<script language='javascript'>alert('Cena kup teraz musi być liczbą!');</script>";
                }
                else if(!empty($buyBid) && (!is_numeric($buyBid)))
                {
                     echo "<script language='javascript'>alert('Cena licytacji musi być liczbą!');</script>";
                }
                else if(strlen($buyPrize) > 16 || strlen($biddingPrize) > 16)
                {
                    echo "<script language='javascript'>alert('Cena może być maksymalnie 16 liczbowa!');</script>";
                }
                else if(strlen($name) > 128)
                {
                    echo "<script language='javascript'>alert('Nazwa może mieć tylko 128 znaków!');</script>";
                }
                else if(strlen($description) > 128)
                {
                    echo "<script language='javascript'>alert('Opis może mieć tylko 512 znaków!');</script>";
                }
                else if(strlen($image) > 256)
                {
                    echo "<script language='javascript'>alert('Link do zdjęcia może mieć tylko 256 znaków!');</script>";
                }
                else
                {
                    //mysql_query("INSERT INTO auctions (name, owner, category, buy_prize, bidding_prize, description, image) VALUES ('".$name."', '".$userLogged[id]."', '".$category."', '".$buyPrize."', '".$biddingPrize."', '".$description."', '".$image."')");                
                    mysql_query("UPDATE auctions SET name = '".$name."', category = '".$category."', buy_prize = '".$buyPrize."', bidding_prize = '".$biddingPrize."', description = '".$description."', image = '".$image."' WHERE id = '".$auctionID."' ");
                    header("Refresh:0; url=index.php");
                    echo "<script language='javascript'>alert('Pomyślnie zedytowałeś swoją aukcję!');</script>";
                }
            }

            $description = htmlspecialchars($auctionInfo['description']);

            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
                            <form class="form-horizontal" action="" method="post">
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" title="Nazwa aukcji">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
                                        <input type="text" class="form-control input-lg align-middle" id="auctionName" name="auctionName" value="'.$auctionInfo[name].'" required>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" title="Kategoria">
                                        <select class="form-control input-lg" name="auctionCategory">
                                            <option value="0">Wybierz kategorie</option>
                                            '.$auctionCategories.'
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" data-html="true" title="Opis aukcji">
                                        <textarea rows="15" class="form-control input-lg col-sm-10" id="auctionDesc" name="auctionDesc" required>'.$description.'</textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" title="Zdjęcie">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-camera"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionImage" name="auctionImage" value="'.$auctionInfo[image].'"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" data-html="true" title="Cena Kup-Teraz">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionBuy" name="auctionBuy" value="'.$auctionInfo[buy_prize].'"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="col-sm-1"></div>
                                    <div class="input-group col-sm-11" data-toggle="tooltip" data-placement="left" data-html="true" title="Cena licytacji">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                        <input type="text" class="form-control input-lg" id="auctionBid" name="auctionBid" value="'.$auctionInfo[bidding_prize].'"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <button type="submit" name="auctionSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Edytuj aukcję!</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            ';
        }
    }
    else if($_GET['action'] === "category")
    {
        $categoryID = intval($_GET['id']);
        $query = mysql_query("SELECT NULL FROM categories WHERE id = ".$categoryID."");

        if(empty($_GET['id']) || mysql_num_rows($query) == 0)
        {
            header("Refresh:0; url=index.php");
            echo "<script language='javascript'>alert('Błąd! Nie ma takiego ID kategorii!');</script>";
        }
        else
        {

        }
    }
    else
    {
        $query = mysql_query("SELECT * FROM categories ORDER BY id");
        while ($row = mysql_fetch_array($query))
        {
            $categories .= '
                <tr>
                    <td style="margin-left: 20px;"><span style="font-size: 17px;"><a href="index.php?action=category&id='.$row["id"].'">'.$row["name"].'</a></span></td>
                </tr>
            ';
        }

        $query = mysql_query("SELECT * FROM auctions ORDER BY id DESC");
        while ($row = mysql_fetch_array($query))
        {
            $query2 = mysql_query("SELECT * FROM categories WHERE id = '".$row[category]."'");
            $cat = mysql_fetch_array($query2);

            if($row["buy_prize"] != 0)
            {
                $buyPrize = '
                    <span style="font-size: 25px; color: #2F3E46;">'.$row["buy_prize"].' zł</span><br />
                    <div style="margin-top: -10px;">
                        <span style="font-size: 12px; color: #FF8C42;">KUP TERAZ</span>
                    </div>
                ';
            }

            if($row["bidding_prize"] != 0)
            {
                $biddingPrize = '
                    <span style="font-size: 25px; color: #2F3E46;">'.$row["bidding_prize"].' zł</span><br />
                    <div style="margin-top: -10px;">
                        <span style="font-size: 12px; color: #FF8C42;">LICYTACJA</span>
                    </div>
                ';
            }

            $auctions .= '
                <tr>
                    <td class="col-xs-2">
                        <a href="index.php?action=showauction&id='.$row[id].'"><img src="'.$row["image"].'" class="img-responsive" /></a>
                    </td>
                    <td class="col-xs-8">
                        <span style="font-size: 25px;"><a href="index.php?action=showauction&id='.$row[id].'"><b>'.$row["name"].'</b></a></span><br />
                        <span style="font-size: 14px;">('.$cat["name"].')</span>
                    </td>
                    <td class="col-xs-2">
                        '.$buyPrize.'
                        '.$biddingPrize.'
                    </td>
                </tr>
                <tr style="height: 60px;"></tr>
            ';
        }

        if(empty($_SESSION['username']) || empty($_SESSION['password']))
        {
            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="alertBox">
                            <span style="font-size: 30px;"><strong>Witaj!</strong></span>
                            <p style="font-size: 20px;">Aby mieć możlwiość korzystania z wszystkich usług, które oferuje serwis aukcyjny zaloguj się na swoje konto (przycisk na górnym pasku) lub klikając bezpośrednio <a href="index.php?action=login">tutaj</a>. Jeśli nie posiadasz swojego konta na naszej witrynie, możesz łatwo je założyć wybierając opcję zarejestruj z górnego paska lub klikając <a href="index.php?action=register">tutaj</a>.</p> 
                        </div>
                    </div>
                </div>

                <div class="container" style="margin-top: 20px;">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="mainBox" style="margin-left: -15px;">
                                <table>
                                    <tr>
                                        <td><span style="font-size: 30px;">Kategorie</span></td>
                                    </tr>
                                    <tr>
                                        <table style="margin-left: 25px;">
                                            '.$categories.'
                                        </table>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mainBox" style="margin-right: -15px; margin-left: -15px;">
                                <table>
                                    '.$auctions.'
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        else
        {
            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="mainBox" style="margin-left: -15px;">
                                <table>
                                    <tr>
                                        <td><span style="font-size: 30px;"><b>Kategorie</b></span></td>
                                    </tr>
                                    <tr>
                                        <table style="margin-left: 25px;">
                                            '.$categories.'
                                        </table>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mainBox" style="margin-right: -15px; margin-left: -15px;">
                                <table>
                                    '.$auctions.'
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    if(isset($_POST['searchSubmit']))
    {
        header("Refresh:0; url=index.php?action=search&item=".$_POST['searchItems']."");
    }
    
    $navBar = '
        <div class="container-fluid">
            <div class="row">
                <div class="navigationBar nav navbar-fixed-top" style="z-index:99999;">
                    <div class="container">
                        <div class="row">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                    <span class="sr-only">Rozwiń nawigację</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="index.php" style="padding-top: 19px;">
                                    <p class="navigationBarLogo">AUKCJE INTERNETOWE</p>
                                    <p class="navigationBarDesc">najlepszy serwis aukcyjny</p>
                                </a>
                            </div>

                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-6" style="margin-top: 13px;">
                                    <form method="POST">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-lg" name="searchItems" placeholder="Wyszukaj interesującą cię rzecz (np. rower, telewizor)">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default input-lg" name="searchSubmit" type="submit">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </button>
                                          </div>
                                        </div>
                                    </form>
                                </div>
                                '.$userPanel.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    $outcome = '
        <!DOCTYPE html>
        <html lang="pl_PL">
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <link href="css/bootstrap.min.css" rel="stylesheet">
                <link href="css/main.css" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Dosis:300" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Maitree:300" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
                
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <!--[if lt IE 9]>
                    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
                <style>
                    .tooltip-head
                    {
                        color: #fff;
                        background: #000;
                        padding: 10px 10px 5px;
                        border-radius: 4px 4px 0 0;
                        text-align: center;
                        margin-bottom: -2px;
                    }
                    .tooltip-head .glyphicon
                    {
                        font-size: 22px;
                        vertical-align: bottom;
                    }
                    .tooltip-head h3
                    {
                        margin: 0;
                        font-size: 18px;
                    }
                </style>
            </head>
            <body>
                '.$navBar.'
                <div style="margin-top: 100px;">
                    '.$index.'
                </div>

                <script src="js/tooltips.js"></script>
            </body>
        </html>
    ';

    echo $outcome;
?>