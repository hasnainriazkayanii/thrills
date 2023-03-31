<?php
$token = '';
$files = [];
$msg = '';
if (isset($_GET['search_date']) && !empty($_GET['search_date'])) {
    $year = date('Y', strtotime($_GET['search_date']));
    $day = date('d', strtotime($_GET['search_date']));
    $month = date('m', strtotime($_GET['search_date']));
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://174.69.26.155:467/api.cgi?cmd=Login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POS',
        CURLOPT_POSTFIELDS => '[
        { "cmd":"Login", "param":{ "User":{ "Version": "0", "userName":"admin", "password":"hudson1535"}}}]',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/octet-stream',
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);


    if (curl_errno($curl)) {
        $errorCode = curl_errno($curl);
        $msg = curl_error($curl);
    }
    curl_close($curl);

    // Check if the response is not empty and is a valid JSON string
    if (!empty($response) && is_string($response) && is_array(json_decode($response, true)) && (json_last_error() == JSON_ERROR_NONE)) {
        // Response is valid JSON
        $data = json_decode($response, true);
        // echo '<pre>',print_r($data);exit;
        if (isset($data[0]) && $data[0]['code'] == 0) {
            if (isset($data[0]['value']) && isset($data[0]['value']['Token']) && isset($data[0]['value']['Token']['name'])) {
                $token = $data[0]['value']['Token']['name'];
            } else {
                $msg = "Can not Fetch Token Error:1";
            }
        } else {
            if (isset($data[0]) && isset($data[0]['error']) && isset($data[0]['error']['detail'])) {
                $msg = 'Api Error Token: ' . $data[0]['error']['detail'];
            } else {
                $msg = "Can not Fetch Token Error:2";
            }
        }
    } else if($msg=='') {
        $msg=  " Request Time out,please refresh the page";
    }
    if ($token != '' && $msg == '') {
        $data = array(
            array(
                "cmd" => "Search",
                "action" => 0,
                "param" => array(
                    "Search" => array(
                        "channel" => 0,
                        "onlyStatus" => 0,
                        "streamType" => "main",
                        "StartTime" => array(
                            "year" => (int) $year,
                            "mon" => (int) $month,
                            "day" => (int) $day,
                            "hour" => 0,
                            "min" => 0,
                            "sec" => 0
                        ),
                        "EndTime" => array(
                            "year" => (int) $year,
                            "mon" => (int) $month,
                            "day" => (int) $day,
                            "hour" => 23,
                            "min" => 59,
                            "sec" => 59
                        )
                    )
                )
            )
        );

        // Convert the associative array to JSON
        $json = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://174.69.26.155:467/api.cgi?cmd=Search&token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/octet-stream',
                'Content-Type: text/plain'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if (!empty($response) && is_string($response) && is_array(json_decode($response, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            // Response is valid JSON
            $data = json_decode($response, true);
            if (isset($data[0]) && $data[0]['code'] == 0) {
                if (isset($data[0]['value']) && isset($data[0]['value']['SearchResult']) && isset($data[0]['value']['SearchResult']['File'])) {
                    $files = $data[0]['value']['SearchResult']['File'];
                }
            } else {
                if (isset($data[0]) && isset($data[0]['error']) && isset($data[0]['error']['detail'])) {
                    $msg = 'Api Error Video: ' . $data[0]['error']['detail'];
                } else {
                    $msg = "No Video  found Error:1";
                }
            }
        } else {
            $msg = "No Video Found Error:2";
        }
    }
}
function bytesToMB($bytes) {
    return number_format($bytes / (1024*1024), 2);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Video Listing</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <form action="video-list.php">
                    <div class="row mb-3">
                        <div class="col-sm-7">
                            <input type="date" required class="form-control" name="search_date" id="date" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php if (isset($_GET['search_date']) && !empty($_GET['search_date'])) { ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Serial No.</th>
                                <th>Name</th>
                                <th>Size</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($files && !empty($files)) {
                                foreach ($files as $key => $file) {
                                    $file_name = $file['name'];
                            ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><a target="_blank" href="play-video.php?file_name=<?= $file_name ?>"><?= $file_name ?></a></td>
                                        <td><?= bytesToMB($file['size']). 'MB'; ?></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="3"> <?= $msg ?></td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>