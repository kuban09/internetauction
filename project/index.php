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
						 <li><a href="index.php?action=messages">Wiadomości</a></li>
                        <li class="divider"></li>
                        <li><a href="index.php?action=newauction">Dodaj aukcję</a></li>
                        <li><a href="index.php?action=myauctions">Moje aukcje</a></li>
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
            $query = mysql_query("SELECT * FROM auctions WHERE name LIKE '%".$searchItem."%' AND buyer = '0'");

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
	 else if($_GET['action'] === "buy")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $auctionID = intval($_GET['id']);
            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);
            $query = mysql_query("SELECT * FROM auctions WHERE id = ".$auctionID." AND buyer = '0'");
            $auctionInfo = mysql_fetch_array($query);

            if(empty($_GET['id']) || mysql_num_rows($query) == 0)
            {
                header("Refresh:0; url=index.php");
                echo "<script language='javascript'>alert('Błąd! Nie ma takiej aukcji lub przedmiot został kupiony!');</script>";
            }
            else
            {
                mysql_query("UPDATE auctions SET buyer = '".$userLogged['id']."' WHERE id = '".$auctionID."'");

                $query = mysql_query("SELECT * FROM users WHERE id = '".$auctionInfo['owner']."'");
                $owner = mysql_fetch_array($query);

                //----------------------[WIADOMOŚĆ DO KUPUJĄCEGO]-------------------------------------
                $message = '
                    <b>Numer aukcji ('.$auctionInfo['id'].')!</b><br /><br />
                    Oto dane kupującego:<br />
                    Nazwa użytkownika: '.$owner['username'].'<br />
                    Imię i nazwisko: '.$owner['firstname'].' '.$owner['surname'].'<br />
                    Email: '.$owner['email'].'<br />
                    Numer telefonu: '.$owner['phone'].'<br /><br /><br />
                    WIADOMOŚĆ ZOSTAŁA WYSŁANA PRZEZ SYSTEM!
                ';
                mysql_query("INSERT INTO messages (owner, recipient, title, message, date, readed) VALUES ('".$userLogged['id']."', '0', 'Zakupiłeś przedmiot (".$auctionInfo['name'].")!', '".$message."', CURRENT_TIMESTAMP(), '0')");
                //-----------------------------------------------------------------------------------------------------

                //----------------------[WIADOMOŚĆ DO SPRZEDAJĄCEGO]-------------------------------------
                $message = '
                    <b>Numer aukcji ('.$auctionInfo['id'].')!</b><br /><br />
                    Oto dane sprzedającego:<br />
                    Nazwa użytkownika: '.$userLogged['username'].'<br />
                    Imię i nazwisko: '.$userLogged['firstname'].' '.$userLogged['surname'].'<br />
                    Email: '.$userLogged['email'].'<br />
                    Numer telefonu: '.$userLogged['phone'].'<br />
                    Numer konta bankowego: '.$userLogged['bank'].'<br />
                    Adres: '.$userLogged['place'].'<br /><br /><br />
                    WIADOMOŚĆ ZOSTAŁA WYSŁANA PRZEZ SYSTEM!
                ';
            mysql_query("INSERT INTO messages (owner, recipient, title, message, date, readed) VALUES ('".$owner['id']."', '0', 'Sprzedałeś przedmiot (".$auctionInfo['name'].")!', '".$message."', CURRENT_TIMESTAMP(), '0')");
            //-----------------------------------------------------------------------------------------------------
                echo "<script language='javascript'>alert('Pomyślnie kupiłeś przedmiot z aukcji!');</script>";
                header("Refresh:0; url=index.php");
            }
        }
    }
    else if($_GET['action'] === "newmessage")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);

            if(isset($_POST['submit']))
            {
                $title = $_POST['title'];
                $recipient = $_POST['username'];
                $message = $_POST['message'];
                
                $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$recipient."'");
                $recipientInfo = mysql_fetch_array($query);

                if(mysql_num_rows($query) == 0)
                {
                    echo "<script language='javascript'>alert('Błąd! Taki użytkownik nie istnieje');</script>";
                    header("Refresh:0; url=index.php?action=newmessage");
                }
                else
                {
                    mysql_query("INSERT INTO messages (owner, recipient, title, message, readed) VALUES ('".$recipientInfo['id']."', '".$userLogged['id']."', '".$title."', '".$message."', '0')");
                    echo "<script language='javascript'>alert('Wiadomość została wysłana!');</script>";
                    header("Refresh:0; url=index.php");
                }
            }
            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
                            <form class="form-horizontal" action="" method="post">
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="input-group col-sm-12">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
                                        <input type="text" class="form-control input-lg align-middle" id="title" name="title" placeholder="Tytuł wiadomości" required>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="input-group col-sm-12">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="text" class="form-control input-lg align-middle" id="username" name="username" placeholder="Odbiorca wiadomości" required>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 30px;">
                                    <div class="input-group col-sm-12">
                                        <textarea rows="15" class="form-control input-lg col-sm-12" id="message" name="message" placeholder="Treść wiadomości..." required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <button type="submit" name="submit" class="btn btn-default input-lg"><span style="font-size: 20px;">Wyślij wiadomość!</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ';
        }
    }
    else if($_GET['action'] === "messages")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $messageID = intval($_GET['id']);

            if(empty($messageID))
            {
                $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
                $userLogged = mysql_fetch_array($query);
                $query = mysql_query("SELECT * FROM messages WHERE owner = '".$userLogged['id']."'");

                if(mysql_num_rows($query) == 0)
                {
                    $messageList = 'Niestety ale nie posiadasz żadnych prywatnych wiadomości! :(';
                }

                while($row = mysql_fetch_array($query))
                {
                    if($row['recipient'] == 0)
                    {
                        $recipient = 'System';
                    }
                    else
                    {
                        $query2 = mysql_query("SELECT username FROM users WHERE id = '".$row['recipient']."'");
                        $rec = mysql_fetch_array($query2);

                        $recipient = $rec['username'];
                    }

                    if($row['readed'] == 0)
                    {
                        $messageList .= '
                            <tr style="font-size: 23px;">
                                <td><a href="index.php?action=messages&id='.$row['id'].'"><b>'.$row['id'].'</b></a></td>
                                <td class="col-md-6"><a href="index.php?action=messages&id='.$row['id'].'"><b>'.$row['title'].'</b></a></td>
                                <td class="col-md-3"><a href="index.php?action=messages&id='.$row['id'].'"><b>'.$row['date'].'</b></a></td>
                                <td class="col-md-3"><a href="index.php?action=messages&id='.$row['id'].'"><b>'.$recipient.'</b></a></td>
                            </tr>
                        ';
                    }
                    else
                    {
                        $messageList .= '
                            <tr style="font-size: 23px;">
                                <td><a href="index.php?action=messages&id='.$row['id'].'">'.$row['id'].'</a></td>
                                <td class="col-md-6"><a href="index.php?action=messages&id='.$row['id'].'">'.$row['title'].'</a></td>
                                <td class="col-md-3"><a href="index.php?action=messages&id='.$row['id'].'">'.$row['date'].'</a></td>
                                <td class="col-md-3"><a href="index.php?action=messages&id='.$row['id'].'">'.$recipient.'</a></td>
                            </tr>
                        ';
                    }
                }

                $index = '
                    <div class="container" style="margin-top: 20px;">
                        <div class="row">
                            <div class="backgroundBox">
                                <table>
                                    <tr style="font-size: 30px;">
                                        <td>ID</td>
                                        <td class="col-md-6">Tytuł</td>
                                        <td class="col-md-3">Data wysłania</td>
                                        <td class="col-md-3">Nadawca</td>
                                    </tr>
                                    <tr style="height: 20px;"></tr>
                                    '.$messageList.'
                                </table>
                            </div>
                        </div>
                    </div>
                ';
            }
            else
            {
                $query = mysql_query("SELECT * FROM messages WHERE id = '".$messageID."' AND owner = '".$userLogged['id']."'");
                $message = mysql_fetch_array($query);

                if($message['readed'] == 0)
                {
                    mysql_query("UPDATE messages SET readed = '1' WHERE id = '".$message['id']."'");
                }

                if(mysql_num_rows($query) == 0)
                {
                    header("Refresh:0; url=index.php?action=messages");
                    echo "<script language='javascript'>alert('Błąd! Nie ma takiej wiadomości bądź nie należy do ciebie!');</script>";
                }
                else
                {
                    $index = '
                        <div class="container" style="margin-top: 20px;">
                            <div class="row">
                                <div class="backgroundBox">
                                    <span style="font-size: 35px;">'.$message['title'].'</span><br /><br />
                                    <span style="font-size: 25px;">'.$message['message'].'</span>
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
        }
    }
	else if($_GET['action'] === "myauctions")
    {
		if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
			$query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);
			$query = mysql_query("SELECT * FROM auctions WHERE owner = '".$userLogged['id']."' AND buyer = '0'");
			
			while($row = mysql_fetch_array($query))
			{
				$query2 = mysql_query("SELECT * FROM categories WHERE id = '".$row['category']."'");
				$category = mysql_fetch_array($query2);
				
				$userAuction .= '
					<tr style="font-size: 20px;">
						<td class="col-md-1">'.$row['id'].'</td>
						<td class="col-md-5">'.$row['name'].' ('.$category['name'].')</td>
						<td class="col-md-1">'.$row['buy_prize'].' zł</td>
						<td class="col-md-2">
							<a href="index.php?action=editauction&id='.$row['id'].'"><input type="button" value="EDYTUJ" /></a>
							<a href="index.php?action=deleteauction&id='.$row['id'].'"><input type="button" value="USUŃ" /></a>
						</td>
					</tr>
				';
			}
			
			$index = '
				<div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
							<table>
								<tr style="font-size: 30px;">
									<td class="col-md-1">ID</td>
									<td class="col-md-5">Nazwa (kategoria)</td>
									<td class="col-md-2">Cena kupna</td>
									<td class="col-md-2">Akcje</td>
								</tr>
								<tr style="height: 10px;"></tr>
								'.$userAuction.'
							</table>
						</div>
					</div>
				</div>
			';
		}
		else
		{
			header("Refresh:0; url=index.php");
			echo "<script language='javascript'>alert('Błąd! Musisz być zalogowany!');</script>";
		}
	}
    else if($_GET['action'] === "showauction")
    {
		$auctionID = intval($_GET['id']);
		$query = mysql_query("SELECT * FROM auctions WHERE id = ".$auctionID."");
            $auctionInfo = mysql_fetch_array($query);

            if(empty($_GET['id']) || mysql_num_rows($query) == 0)
            {
                header("Refresh:0; url=index.php");
                echo "<script language='javascript'>alert('Błąd! Taka aukcja nie istnieje!');</script>";
            }
		
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
			if(isset($_POST['submit']))
            {
                $message = $_POST['commentary'];
				$query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
				$userLogged = mysql_fetch_array($query);

                mysql_query("INSERT INTO comments (owner, auction, message) VALUES ('".$userLogged[id]."', '".$auctionID."', '".$message."')");
                echo "<script language='javascript'>alert('Pomyślnie dodałeś komentarz!');</script>";
                header("Refresh:0; url=index.php?action=showauction&id=".$auctionID."");
            }
			
			$addComment = '
				<div style="padding-top: 60px;">
                                <form class="form-horizontal" action="" method="post">
                                    <div class="form-group" style="padding-bottom: 30px;">
                                        <div class="col-sm-1"></div>
                                        <div class="input-group col-sm-12">
                                            <textarea rows="6" class="form-control input-lg col-sm-10" name="commentary" placeholder="Wpisz tutaj swój komentarz do aukcji..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-5 col-sm-11">
                                            <button type="submit" name="submit" class="btn btn-default input-lg"><span style="font-size: 20px;">WYŚLIJ KOMENTARZ!</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
			';
		}
            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);

            if($auctionInfo["buy_prize"] != 0)
            {
                $buyPrize = '
                    <div style="float: left;"><span style="font-size: 25px;">Cena KUP-TERAZ:</span> <span style="font-size: 27px; color: #FF8C42;">'.$auctionInfo[buy_prize].' zł</span></div>
                    <div style="float: right;"><a href="index.php?action=buy&id='.$auctionInfo['id'].'"><input type="button" class="button" value="KUP PRZEDMIOT" /></a></div>
                      <div style="float: none;"></div>
                ';
            }

            if($auctionInfo["bidding_prize"] != 0)
            {
                $biddingPrize = '
                    <div style="float: left;"><span style="font-size: 25px;">Cena KUP-TERAZ:</span> <span style="font-size: 27px; color: #FF8C42;">'.$auctionInfo[bidding_prize].' zł</span></div>
                    <div style="float: right;"><input type="button" class="button" value="LICYTUJ" /></div>
                    <div style="float: none;"></div>
                ';
            }

            $query = mysql_query("SELECT * FROM categories WHERE id = '".$auctionInfo['category']."'");
            $category = mysql_fetch_array($query);
            $query = mysql_query("SELECT * FROM comments WHERE auction = '".$auctionID."' ORDER BY id DESC");

            if(mysql_num_rows($query) == 0)
            {
                $comments = '
                    <br /><br /><span style="font-size: 25px;">Niestety ta aukcja nie posiada żadnych komentarzy! :(</span>
                ';
            }
            else
            {
                while($row = mysql_fetch_array($query))
                {
                    $query2 = mysql_query("SELECT * FROM users WHERE id = '".$row[owner]."'");
                    $user = mysql_fetch_array($query2);

                    $comments .= '
                        <table style="margin-top: 40px;">
                            <tr>
                                <td class="col-md-2"><img src="'.$user[avatar].'" style="height: 100px; width: 100px;" class="img-responsive" /><span style="font-size: 19px; margin-left: 15px;">'.$user[username].'</span></td>
                                <td style="width: 10px;"></td>
                                <td class="col-md-10" valign="top"><span style="font-size: 20px;">'.$row[message].'</span></td>
                            </tr>
                        </table>
                    ';
                }
            }

            $index = '
                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
                            <span style="font-size: 40px; text-transform: uppercase;">'.$auctionInfo[name].'</span> <span style="font-size: 20px;">(id: '.$auctionInfo[id].')</span>
                            <table style="margin-top: 15px;">
                                <tr>
                                    <td class="col-md-4"><img src="'.$auctionInfo[image].'" class="img-responsive" /><center><span style="font-size: 20px;">('.$category[name].')</span></center></td>
                                    <td class="col-md-8" valign="top">
                                        '.$buyPrize.'<br /><br />
                                        '.$biddingPrize.'
                                    </td>
                                </tr>
                            </table>
                            <div style="padding-top: 50px;">
                                '.$auctionInfo[description].'
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="container" style="margin-top: 20px;">
                    <div class="row">
                        <div class="backgroundBox">
                            <span style="font-size: 30px;">KOMENTARZE</span>
                            '.$comments.'
                            '.$addComment.'
                        </div>
                    </div>
                </div>
            ';
    }
	else if($_GET['action'] === "deleteauction")
    {
        if(!empty($_SESSION['username']) || !empty($_SESSION['password']))
        {
            $auctionID = intval($_GET['id']);
            $query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
            $userLogged = mysql_fetch_array($query);
            $query = mysql_query("SELECT * FROM auctions WHERE id = ".$auctionID."");
			$auction = mysql_fetch_array($query);

            if(empty($_GET['id']) || mysql_num_rows($query) == 0)
            {
                header("Refresh:0; url=index.php");
                echo "<script language='javascript'>alert('Błąd! Nie ma takiej aukcji lub nie ty ją dodałeś!');</script>";
            }
			else
			{
				if($auction['owner'] == $userLogged['id'])
				{
					header("Refresh:0; url=index.php");
					echo "<script language='javascript'>alert('Pomyślnie usunąłeś aukcję!');</script>";
					mysql_query("DELETE FROM auctions WHERE id = '".$auctionID."'");
				}
				else
				{
					header("Refresh:0; url=index.php");
					echo "<script language='javascript'>alert('Błąd! Nie jesteś właścicielem aukcji!');</script>";
				}
			}
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
										<a href="index.php?action=deleteauction&id='.$auctionInfo["id"].'"> <button type="button" name="deleteSubmit" class="btn btn-default input-lg"><span style="font-size: 20px;">Usuń aukcję!</span></button></a>
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
			$query = mysql_query("SELECT * FROM categories ORDER BY id");
        while ($row = mysql_fetch_array($query))
        {
            $categories .= '
                <tr>
                    <td style="margin-left: 20px;"><span style="font-size: 17px;"><a href="index.php?action=category&id='.$row["id"].'">'.$row["name"].'</a></span></td>
                </tr>
            ';
        }
		
			$query = mysql_query("SELECT * FROM auctions WHERE category = ".$categoryID."");
			
			if(mysql_num_rows($query) == 0)
			{
				$auctions = 'Brak jakichkolwiek aukcji w tej kategorii!';	
			}
			
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
                <div class="row">
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

        $query = mysql_query("SELECT * FROM auctions WHERE buyer = '0' ORDER BY id DESC");
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
		
		$query = mysql_query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND password LIKE '".$_SESSION['password']."'");
         $userLogged = mysql_fetch_array($query);
         $query = mysql_query("SELECT * FROM messages WHERE owner = '".$userLogged['id']."' AND readed = '0'");
 
         while($row = mysql_fetch_array($query))
         {
             $unreadedMessage .= '
                 <div class="container" style="margin-top: 20px;">
                     <div class="row">
                         <div class="alertMessageBox">
                             <span style="font-size: 20px;"><strong>Nieprzeczytana wiadomość!</strong></span>
                             <p style="font-size: 15px;">Tytuł wiadomości: <a href="index.php?action=messages&id='.$row['id'].'">'.$row['title'].'</a></p> 
                         </div>
                     </div>
                 </div>
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
			'.$unreadedMessage.'
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