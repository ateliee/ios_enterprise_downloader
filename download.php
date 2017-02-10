<?php
if(!isset($_SERVER["REQUEST_URI"]) || !preg_match('/([a-zA-Z0-9]+)\/([^\/]+)\.([a-zA-Z0-9]+)$/',$_SERVER["REQUEST_URI"],$matchs)){
    return false;
}
$download_key = $matchs[1];
$filename = $matchs[2];
$extension = strtolower($matchs[3]);
$basename = $filename.".".$extension;
if(!in_array($extension,["ipa","plist"])){
    return false;
}
if($download_key != DOWNLOAD_KEY){
    die("Access Error");
}
if(!isset($DOWNLOAD_FILES[$filename])){
    die("Access Error");
}
$PLIST_INFO = $DOWNLOAD_FILES[$filename];

if($extension == "ipa"){
    $path_file = dirname(__FILE__).'/files/'.$basename;
    if(file_exists($path_file) && is_file($path_file)){
        if (($content_length = filesize($path_file)) == 0) {
            die("Error: File size is 0");
        }
        header("Content-Disposition: File Transfer");
        header("Content-Length: ".$content_length);
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="'.$basename.'"');
        if (!readfile($path_file)) {
            die("Cannot read the file");
        }
        exit;
    }
    die("Access Error");
}

$DOWNLOAD_URL = sprintf(IPA_URL,$filename);

header('Content-Description: File Transfer');
header('Content-Type: application/x-plist');
header('Content-Disposition: attachment; filename="'.$basename.'"');
echo '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">';
?>
<plist version="1.0">
    <dict>
        <key>items</key>
        <array>
            <dict>
                <key>assets</key>
                <array>
                    <dict>
                        <key>kind</key>
                        <string>software-package</string>
                        <key>url</key>
                        <string><?php echo $DOWNLOAD_URL; ?></string>
                    </dict>
                </array>
                <key>metadata</key>
                <dict>
                    <key>bundle-identifier</key>
                    <string><?php echo BUNDLE_ID; ?></string>
                    <key>bundle-version</key>
                    <string><?php echo $PLIST_INFO["version"]; ?></string>
                    <key>kind</key>
                    <string>software</string>
                    <key>title</key>
                    <string><?php echo $PLIST_INFO["name"]; ?></string>
                </dict>
            </dict>
        </array>
    </dict>
</plist><?php return true; ?>