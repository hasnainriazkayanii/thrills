<?php
$file_name = '';
$token = '';
$msg='';
if (isset($_GET['file_name']) && !empty($_GET['file_name'])) {
    $file_name = $_GET['file_name'];
    // echo $file_name;exit;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://174.69.26.155:467/api.cgi?cmd=Login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POS',
        CURLOPT_POSTFIELDS => '[
    { "cmd":"Login", "param":{ "User":{ "Version": "0", "userName":"admin", "password":"hudson1535"
    }
    }
    }
    ]',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/octet-stream',
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);


    if (curl_errno($curl)) {
        echo " Request Time out,please refresh the page";
    }
    curl_close($curl);

    // Check if the response is not empty and is a valid JSON string
    if (!empty($response) && is_string($response) && is_array(json_decode($response, true)) && (json_last_error() == JSON_ERROR_NONE)) {
        // Response is valid JSON
        $data = json_decode($response, true);
        // echo '<pre>',print_r($data);exit;
       if(isset($data[0]) && $data[0]['code']==0){
            if(isset($data[0]['value']) && isset($data[0]['value']['Token']) && isset($data[0]['value']['Token']['name'])){
                $token = $data[0]['value']['Token']['name'];
            }
            else{
                $msg= "Can not Fetch Token Error:1";
            }
       }
       else{
        if(isset($data[0]) && isset($data[0]['error']) && isset($data[0]['error']['detail'])){
            $msg='Api Error Token: '.$data[0]['error']['detail'];
        }
        else{
            $msg= "Can not Fetch Token Error:2";
        }
       }
    } else {
        echo " Request Time out,please refresh the page";
    }
}
if ($token != '' && $file_name != '') {
    $file_name =  'http://174.69.26.155:467/cgi-bin/api.cgi?token=' . $token . '&cmd=Playback&source=' . $file_name . '&output=' . $file_name;
    // $api_url = 'https://api.example.com/videos/video.mp4';

// Initialize cURL session
// $ch = curl_init();

// Set cURL options
// curl_setopt($ch, CURLOPT_URL, $api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// // Execute cURL session
// $data = curl_exec($ch);

// // Close cURL session
// curl_close($ch);

// // Output the video data
// header('Content-type: video/mp4');
// echo $data;
// exit;
}
?>

<head>
    
    <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />

    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->
</head>

<body>
    <?php if ($msg == '') { ?>
        <video id="my-video" class="video-js" controls preload="auto" width="640" height="264" poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
            <source src="<?= $file_name ?>" type="video/mp4" />

            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a
                web browser that
                <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
            </p>
        </video>
    <?php } else {
                echo $msg;

    } ?>

    <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script>
</body>