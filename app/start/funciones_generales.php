<?php
function fescape_string($vcparam)
{
    $vcparam = trim($vcparam);
    $vcparam .= ""; //Parse String;
    $len = strlen($vcparam);
    $escapeCount = 0;
    $targetString = '';
    for ($offset = 0; $offset < $len; $offset++) {
        switch ($c = $vcparam[$offset]) {
            case "'":
                // Escapes this quote only if its not preceded by an unescaped backslash
                if ($escapeCount % 2 == 0)
                    $targetString .= "\\";
                $escapeCount = 0;
                $targetString .= $c;
                break;
            case '"':
                // Escapes this quote only if its not preceded by an unescaped backslash
                if ($escapeCount % 2 == 0)
                    $targetString .= "\\";
                $escapeCount = 0;
                $targetString .= $c;
                break;
            case '\\':
                if ($escapeCount % 2 == 0)
                    $targetString .= "\\";
                $escapeCount = 0;
                $targetString .= $c;
                /*
                  $escapeCount++;
                  $targetString.=$c;
                 */
                break;
            default:
                $escapeCount = 0;
                $targetString .= $c;
        }
    }
    return $targetString;
}

function generarClaveAleatoria($length = 8, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $clave = '';
    $max = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++)
    {
        $clave .= $chars[random_int(0, $max)];
    }

    return $clave;
}

function uploadFile($file, array $valid_ext)
{
    if ($file["error"] == UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (in_array($ext, $valid_ext)) {
            $fileName = date("Ymdhis") . rand(1000, 9999) . ".$ext";
            if (move_uploaded_file($file["tmp_name"], env('APP_PATH') . "/public/files/$fileName")) {
                return $fileName;
            }
        }
    }

    return null;
}