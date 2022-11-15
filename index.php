<?php
require __DIR__.'/vendor/autoload.php';

if(isset($_POST['upload'])) {
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowType = array('jpg', 'png', 'jpeg', 'gif');
    if(in_array($fileType, $allowType)){
        $image_source = file_get_contents($_FILES['fileToUpload']['tmp_name']);
        $client = new GuzzleHttp\Client();
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "Filenya gaboleh lebih dari 5MB bang";
        } else {
        $response = $client->request('POST', 'https://telegra.ph/upload', [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => $image_source,
                    'filename' => 'blob'
                ]
            ]
        ]);

        $result = $response->getBody()->getContents();
        $json = json_decode($result);
    }
        if(!empty($json[0]->src)){
            echo "upload sukses lur <br>";
            echo "Image url : https://telegra.ph" . $json[0]->src . "<br>";
            echo "<br><img src='https://telegra.ph" . $json[0]->src . "' alt='' height='500px' ><br><br><br>";
        } else {
            echo "";
        }
    } else {
        echo "Gagal! Format file tidak didukung<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Picture</title>
</head>
<style>
    body{
        background-color: #fff;
        text-align: center;
        display: block;
    }
</style>
<body>

<div class="container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <label for="select-file">Select Image</label>
            <input name="fileToUpload" id="fileToUpload" type="file">
            <input value="Click to upload" name="upload" type="submit">
        </form>
        <br>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Refresh</a>
</div>
</body>
</html>
