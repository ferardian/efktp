<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GenerateCertificate extends Command
{
	protected $signature = 'cert:generate {--name=}';
	protected $description = 'Generate PEM and PFX certificates based on environment configuration';

	public function handle()
	{
		$timestamp = Carbon::now()->format('Ymd_His');

		// Ambil nama dari option atau dari ENV
		$folderName = $this->option('name') ?: env('CERT_COMMON_NAME', 'default');
		$folderName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $folderName); // sanitize
		$dir = storage_path("app/certs/{$folderName}");

		File::makeDirectory($dir, 0777, true, true);
		$this->info("📁 Folder created: {$dir}");

		// Ambil nilai dari .env
		$country = env('CERT_COUNTRY', 'ID');
		$state = env('CERT_STATE', 'JAWA TENGAH');
		$locality = env('CERT_LOCALITY', 'PEKALONGAN');
		$organization = env('CERT_ORG', '');
		$organizationalUnit = env('CERT_ORG_UNIT', 'IT Department');
		$commonName = env('CERT_COMMON_NAME', 'rsiaaisyiyah.com');
		$email = env('CERT_EMAIL', 'info@rsiaaisyiyah.com');
		$password = env('CERT_PASSWORD', 'secret123');

		$dn = [
			"countryName" => $country,
			"stateOrProvinceName" => $state,
			"localityName" => $locality,
			"organizationName" => $organization,
			"organizationalUnitName" => $organizationalUnit,
			"commonName" => $commonName,
			"emailAddress" => $email,
		];

		// Generate keypair
		$this->info("🔑 Generating keypair...");
		$privkey = openssl_pkey_new([
			"private_key_bits" => 2048,
			"private_key_type" => OPENSSL_KEYTYPE_RSA,
		]);

		$csr = openssl_csr_new($dn, $privkey);
		$cert = openssl_csr_sign($csr, null, $privkey, 365);

		// File path
		$privateKeyPem = "{$dir}/private.pem";
		$publicKeyPem = "{$dir}/public.pem";
		$pfxFile = "{$dir}/certificate.pfx";

		// Export files
		openssl_pkey_export_to_file($privkey, $privateKeyPem, $password);
		openssl_x509_export_to_file($cert, $publicKeyPem);

		$pfx = null;
		openssl_pkcs12_export($cert, $pfx, $privkey, $password);
		file_put_contents($pfxFile, $pfx);

		// Set permission 0777
		chmod($privateKeyPem, 0777);
		chmod($publicKeyPem, 0777);
		chmod($pfxFile, 0777);
		chmod($dir, 0777);

		$this->info("\n✅ Certificate generated successfully!");
		$this->info("📄 Private Key : {$privateKeyPem}");
		$this->info("📄 Public Cert : {$publicKeyPem}");
		$this->info("📄 PFX File    : {$pfxFile}");
		$this->info("🔐 Password    : {$password}");
	}
}
