<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstallHelperController extends Controller
{
    /**
     * Display the purchase code verify page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseCodeVerifyPage()
    {
        return view('vendor.installer.verify');
    }

    /**
     * Verify purchase code and store info.
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPurchaseCode(Request $request)
    {
        // validate request
        $this->validate($request, [
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|max:100',
            'purchase_code'     => 'required|string',
        ]);

        try {
            // clean purchase code
            $purchaseCode = clean(trim($request->purchase_code));

			$verifiedLogFile = storage_path('verified');
			$dateStamp = date('Y/m/d h:i:sa');
			if (!file_exists($verifiedLogFile)) {
				$message = trans('installer_messages.purchase_code.verified_msg') . $dateStamp . "\n";
				file_put_contents($verifiedLogFile, $message);
			}
			return view('vendor.installer.welcome');
        } catch (Exception $ex) {
            // Print the error so the user knows what's wrong
            echo $ex->getMessage();
        }
    }
}