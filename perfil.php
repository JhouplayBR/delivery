<?php
if (isset($_POST['ensv'])) {
    $imagem = $_FILES['photo'];
    if (Database::uploadCheck($imagem)) {
        unlink('uploads/'.$_SESSION['user_img']);
        move_uploaded_file($imagem['tmp_name'],'uploads/'.$imagem['name']);
        $filename =  $_FILES['photo']['name'];
// Maximum width and height
        $width = 200;
        $height = 200;
// Get new dimensions
        $width_orig = 100;
        $height_orig = 100;
        list($width_orig, $height_orig) = getimagesize('uploads/'.$filename);
        $ratio_orig = $width_orig / $height_orig;
        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }
// Resampling the image
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg('uploads/'.$filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0,
            $width, $height, $width_orig, $height_orig);
// Display of output image
        imagejpeg($image_p, 'uploads/'.$filename);
        $insert = Mysql::Conectar()->prepare('UPDATE `usuarios` SET img_user = ? WHERE id = ?');
        $insert->execute(array($filename,$_SESSION['user_id']));
        $_SESSION['user_img'] = $filename;
        echo '<script>window.location.href="http://localhost/delivery/perfil"</script>';
    }else{
        echo 'imagem muito pesada';
    }
}
?>
<h2 style="margin: 50px 0;text-align: center;color: #cccccc">PERFIL</h2>
<div style="text-align: center;">
    <label for="FileInput">
        <img src="uploads/<?php echo $_SESSION['user_img'];?>" style="cursor:pointer;min-width: 180px"  />
    </label>
    <form method="post" enctype="multipart/form-data">
        <input type="file" id="FileInput" style="cursor: pointer;  display: none" name="photo"/>
        <input class="up" type="submit" id="Up" value="Mudar foto" name="ensv"/>
    </form>
</div>
<div class="perfil__infos">
    <label for="exampleFormControlTextarea1">Nome:</label>
    <input class="form-control" type="text" name="nome_user" value="<?php echo $_SESSION['user_name']?>" readonly>
    <label for="exampleFormControlTextarea1">Senha:</label>
    <input class="form-control" type="password" name="nome_user" value="*************" readonly>
</div>
