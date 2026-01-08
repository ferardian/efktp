<?php

$init = parse_ini_file("satusehat.ini");
$client_id = $init["client_id"];
$client_secret = $init["client_secret"];
$auth_url = $init["auth_url"];
$api_url = $init["api_url"];
$environment = $init["environment"];

include('auth.php');
include('function.php');

// nama petugas/operator Fasilitas Pelayanan Kesehatan (Fasyankes) yang akan melakukan validasi
$agent_name = $_GET['nama'];

// NIK petugas/operator Fasilitas Pelayanan Kesehatan (Fasyankes) yang akan melakukan validasi
$agent_nik = $_GET['nik'];

// auth to satusehat
$auth_result = authenticateWithOAuth2($client_id, $client_secret, $auth_url);

// Example usage
$json = generateUrl($agent_name, $agent_nik, $auth_result, $api_url, $environment);

$validation_web = json_decode($json, TRUE);

?><html>

<head>
  <script type="text/javascript">
    var url = "<?php echo $validation_web["data"]["url"] ?>"
    var petugas = "<?php echo $agent_name ?>"
    loadFormNewTab()

    function loadFormNewTab() {
      window.open(url, "_blank")
      window.close()
    }
  </script>
</head>

<body>
  <button onclick="loadFormPopup()">KYC Pasien Popup</button>
  <button onclick="loadFormNewTab()">KYC Pasien New Tab</button>
</body>

</html>