<?php
require_once 'db_connect.php';
require_once 'vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naamStudent = $_POST["naamStudent"];
    $studentNum = $_POST["studentNum"];
    $examenNamen = isset($_POST["examenNaam"]) ? $_POST["examenNaam"] : [];
    $examenCodes = isset($_POST["examenCode"]) ? $_POST["examenCode"] : [];
    $bewijs = (isset($_POST["bewijs"]) && $_POST["bewijs"] == "ja") ? uploadFile() : "nee";

    if (count($examenNamen) != count($examenCodes)) {
        echo "Error: The number of exam names and codes do not match.";
    } else {
        // Aanmaken van de aanvraag zodat we daarna de examens kunnen koppelen aan dit ID
        $query = "INSERT INTO aanvraag (naamStudent, studentNum, bewijs) 
                    VALUES (:naamStudent, :studentNum, :bewijs)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':naamStudent', $naamStudent);
        $stmt->bindParam(':studentNum', $studentNum);
        $stmt->bindParam(':bewijs', $bewijs);
        $stmt->execute();

        $primaryKey = $conn->lastInsertId();

        $index = 0;
        foreach ($examenCodes as $code) {
            $query = "INSERT INTO aanvraag_examens (examenNaam, examenCode, aanvraag_id) 
                        VALUES (:examenNaam, :examenCode, :aanvraag_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':examenNaam', $examenNamen[$index]);
            $stmt->bindParam(':examenCode', $code);
            $stmt->bindParam(':aanvraag_id', $primaryKey);
            $stmt->execute();
            $index++;
        }


        header("Location: test.php?$naamStudent, $studentNum, $examenCode, $examenNaam,$bewijs");

    }
}


function uploadFile()
{
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['pdf'];

        if (in_array($fileExt, $allowed)) {
            if ($fileSize < 1000000) { // 1MB file size limit
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                return $fileDestination;
            } else {
                echo "Sorry, your file is too large.";
            }
        } else {
            echo "Sorry, only PDF files are allowed.";
        }
    }
    return "nee";
}
?>
