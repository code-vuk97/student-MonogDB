<?php
require 'vendor/autoload.php'; // include Composer's autoloader
require('connection.php');
require('crud.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Studenti MongoDB</title>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/58a10b4cf7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <nav>
            <ul>
                <li>
                    <div class="menu-item">
                        <p>STUDENTI</p>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <p>PREDMETI</p>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <p>OCENE</p>
                    </div>
                </li>
            </ul>

        </nav>
        <div id="container">
            <div id="studenti">
                <table id="tab-studenti">
                    <tr>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Indeks</th>
                        <th></th>

                    </tr>
                    <!-- add php here -->
                    <?php


                    $result_student = $coll_student->find();

                    $counter = 0;
                    foreach ($result_student as $student) :
                        $counter += 1;
                        $id = "stud" . $counter;
                    ?>
                        <tr id=<?php echo $id; ?>>
                            <td><?php echo $student['ime'] ?></td>
                            <td><?php echo $student['prezime'] ?></td>
                            <td><?php echo $student['_id'] ?></td>
                            <td> <i class="fas fa-caret-square-down showOcene"></i></td>
                        </tr>
                        <?php
                        $result_stud_ocene = $coll_ocena->find(['student' => $student['_id']]);
                        if ($result_stud_ocene != "") {

                        ?>
                            <tr>
                                <td colspan="4" id="<?php echo 'ocene-' . $id ?>" hidden>
                                    <table>
                                        <tr>
                                            <th>Predmet</th>
                                            <th>Ocena</th>
                                        </tr>
                                        <?php foreach ($result_stud_ocene as $ocena) {
                                        ?>
                                            <tr>
                                                <td><?php echo $coll_predmet->findOne(["_id" => $ocena['predmet']])['naziv']; ?></td>
                                                <td><?php echo $ocena['ocena']; ?></td>
                                            <?php } ?>
                                            </tr>
                                    </table>
                                </td>
                            </tr>
                        <?php
                        } else {
                            echo "nema ocena";
                        }

                        ?>


                    <?php endforeach; ?>

                </table>
                <button id="prikaziDodaj">Dodaj Studenta</button>
                <button id="deleteStudent">Obrisi Studenta</button>

                <div id="studentaPrikaz" hidden>
                    <form method="post" name="dodajStudenta">
                        <input type="text" name="ime" placeholder="Ime">
                        <input type="text" name="prezime" placeholder="Prezime">
                        <input type="text" name="index" placeholder="Index">
                        <input type="submit" name="dodaj" value="Dodaj" id="dugmedodaj">
                        <input type="submit" name="obrisi" value="Obrisi" id="dugmeobrisi">
                    </form>
                </div>
            </div>

            <div id="predmeti" hidden></div>
            <div id="ocene" hidden> </div>


        </div>
    </main>
    <script>
        var ocene = document.getElementsByClassName("showOcene");
        for (let o of ocene) {
            o.addEventListener('click', function() {
                var el = document.getElementById("ocene-" + o.parentNode.parentNode.id);
                if (el.hidden === true) {
                    el.hidden = false;
                } else {
                    el.hidden = true;
                }
            });
        }

        document.getElementById("prikaziDodaj").addEventListener('click', function() {
            var el = document.getElementById("studentaPrikaz");
            if (el.hidden == true) {
                el.hidden = false;
            } else {
                el.hidden = true;
            }
            document.getElementById("dugmeobrisi").hidden=true;
            document.getElementById("dugmedodaj").hidden=false;
        });

        document.getElementById("deleteStudent").addEventListener('click', function() {
            var selektovan = document.getElementsByClassName("selected")[0];
            var student = getSelectedStudent(selektovan);
            var formFields=[document.getElementsByName("dodajStudenta")[0].childNodes[1], document.getElementsByName("dodajStudenta")[0].childNodes[3], document.getElementsByName("dodajStudenta")[0].childNodes[5]];
            for (let i = 0; i < 3; i++) {
                formFields[i].value = student[i];
            }

            var el = document.getElementById("studentaPrikaz");
            if (el.hidden == true) {
                el.hidden = false;
            } else {
                el.hidden = true;
            }
            document.getElementById("dugmedodaj").hidden=true;
            document.getElementById("dugmeobrisi").hidden=false;
        });

        function getSelectedStudent(element){
            var elementi = element.childNodes;
            ime = elementi[1].innerText;
            prezime = elementi[3].innerText;
            indeks = elementi[5].innerText;

            return [ime,prezime,indeks];
        }

        $("#tab-studenti tr").click(function() {
            $(this).addClass('selected').siblings().removeClass('selected');
            var value = $(this).find('td:first').html();
        });



    </script>
</body>

</html>